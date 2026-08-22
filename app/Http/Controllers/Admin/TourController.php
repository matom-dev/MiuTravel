<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Location;
use App\Models\TourGuide;
use App\Models\BookTour;
use App\Models\TourSchedule;
use App\Http\Requests\TourRequest;
use App\Jobs\CreateAppNotification;
use App\Services\AdminAuditLogger;

class TourController extends Controller
{
    //
    protected $tour;
    //
    /**
     * HomeController constructor.
     */
    protected $auditLogger;

    public function __construct(Tour $tour, Location $location, AdminAuditLogger $auditLogger)
    {
        view()->share([
            'tour_active' => 'active',
            'status' => $tour::STATUS,
        ]);

        view()->composer(['admin.tour.*'], function ($view) use ($location) {
            $view->with('locations', $location->where('l_status', 1)->get());
            $view->with('tourLeaders', TourGuide::active()->canBeLeader()->orderBy('tg_name')->get());
            $view->with('tourGuideStaff', TourGuide::active()->canBeGuide()->orderBy('tg_name')->get());
        });

        $this->tour = $tour;
        $this->auditLogger = $auditLogger;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $tours = Tour::with(['location', 'guideAssignments.guide'])
            ->withCount([
                'activeSchedules',
                'guideAssignments',
            ]);
        if ($request->t_title) {
            $tours->where('t_title', 'like', '%'.$request->t_title.'%');
        }

        if ($request->filled('t_status') && array_key_exists((int) $request->t_status, Tour::STATUS)) {
            $tours->where('t_status', (int) $request->t_status);
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $tours->orderBy('id');
        } elseif ($sort === 'price_asc') {
            $tours->orderBy('t_price_adults');
        } elseif ($sort === 'price_desc') {
            $tours->orderByDesc('t_price_adults');
        } else {
            $tours->orderByDesc('id');
        }

        $tours = $tours->paginate(NUMBER_PAGINATION)->withQueryString();
        $tourIds = $tours->getCollection()->pluck('id');
        $guestExpression = 'COALESCE(b_number_adults,0) + COALESCE(b_number_children,0) + COALESCE(b_number_child6,0) + COALESCE(b_number_child2,0)';
        $pendingGuests = BookTour::whereIn('b_tour_id', $tourIds)
            ->where('b_status', BookTour::STATUS_PENDING)
            ->select('b_tour_id')
            ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
            ->groupBy('b_tour_id')
            ->pluck('guests_count', 'b_tour_id');
        $confirmedGuests = BookTour::whereIn('b_tour_id', $tourIds)
            ->whereIn('b_status', [BookTour::STATUS_CONFIRMED, BookTour::STATUS_PAID, BookTour::STATUS_COMPLETED])
            ->select('b_tour_id')
            ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
            ->groupBy('b_tour_id')
            ->pluck('guests_count', 'b_tour_id');

        $tours->getCollection()->each(function ($tour) use ($pendingGuests, $confirmedGuests) {
            $tour->pending_guests_count = (int) ($pendingGuests[$tour->id] ?? 0);
            $tour->confirmed_guests_count = (int) ($confirmedGuests[$tour->id] ?? 0);
        });

        return view('admin.tour.index', compact('tours'));
    }

    public function calendar(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'tour_id' => 'nullable|integer',
        ]);

        $dateFrom = $validated['date_from'] ?? now()->startOfMonth()->toDateString();
        $dateTo = $validated['date_to'] ?? now()->endOfMonth()->toDateString();
        $tourId = $validated['tour_id'] ?? null;

        $schedules = TourSchedule::with('tour')
            ->whereDate('ts_start_date', '>=', $dateFrom)
            ->whereDate('ts_start_date', '<=', $dateTo)
            ->when($tourId, function ($query) use ($tourId) {
                $query->where('ts_tour_id', $tourId);
            })
            ->orderBy('ts_start_date')
            ->get();

        $requestedBookings = BookTour::with('tour')
            ->whereIn('b_status', [BookTour::STATUS_PENDING, BookTour::STATUS_CONFIRMED, BookTour::STATUS_PAID])
            ->whereDate('b_start_date', '>=', $dateFrom)
            ->whereDate('b_start_date', '<=', $dateTo)
            ->when($tourId, function ($query) use ($tourId) {
                $query->where('b_tour_id', $tourId);
            })
            ->orderBy('b_start_date')
            ->get();

        $tours = Tour::where('t_status', 1)->orderBy('t_title')->get(['id', 't_title']);

        return view('admin.tour.calendar', compact('schedules', 'requestedBookings', 'tours', 'dateFrom', 'dateTo', 'tourId'));
    }

    public function preview($id)
    {
        $tour = Tour::with(['location', 'guideAssignments.guide'])->findOrFail($id);

        return view('admin.tour.preview', compact('tour'));
    }

    public function publish(Request $request, $id, $status)
    {
        $newStatus = (int) $status;

        if (!array_key_exists($newStatus, Tour::STATUS)) {
            abort(404);
        }

        $tour = Tour::findOrFail($id);
        $oldStatus = (int) $tour->t_status;
        $tour->t_status = $newStatus;
        $tour->save();

        $this->auditLogger->log('tour.status_published', $tour, [
            'title' => $tour->t_title,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ], $request);

        return redirect()->back()->with('success', 'Cập nhật trạng thái tour thành công');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.tour.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TourRequest $request)
    {
        //
        \DB::beginTransaction();
        try {
            $tour = $this->tour->createOrUpdate($request);
            $this->notifyPendingReview($tour, 'created');
            $this->auditLogger->log('tour.created', $tour, [
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
            ], $request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $tour = Tour::with('guideAssignments')->findOrFail($id);

        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        return view('admin.tour.edit', compact('tour'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TourRequest $request, $id)
    {
        //
        \DB::beginTransaction();
        try {
            $oldStatus = (int) Tour::whereKey($id)->value('t_status');
            $tour = $this->tour->createOrUpdate($request, $id);
            $this->notifyPendingReview($tour, 'updated', $oldStatus);
            $this->auditLogger->log('tour.updated', $tour, [
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
            ], $request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }

    /**
     * Xóa một ảnh khỏi album tour
     *
     * @param  int  $id
     * @param  int  $index
     * @return \Illuminate\Http\Response
     */
    public function removeAlbumImage($id, $index)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $album = $tour->t_anbum_image ? $tour->t_anbum_image : [];
        if (isset($album[$index])) {
            $removedImage = $album[$index];
            array_splice($album, $index, 1);
            // Gán array trực tiếp, $casts => 'array' sẽ tự json_encode khi lưu
            $tour->t_anbum_image = array_values($album);
            $tour->save();
            delete_uploaded_image($removedImage);
        }

        return redirect()->back()->with('success', 'Đã xóa ảnh khỏi album');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
        $tour = Tour::find($id);
        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        try {
            $this->auditLogger->log('tour.deleted', $tour, [
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
            ]);
            $tour->delete();
            return redirect()->back()->with('success', 'Xóa thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể xóa dữ liệu');
        }
    }

    private function notifyPendingReview(Tour $tour, string $action, ?int $oldStatus = null): void
    {
        if ((int) $tour->t_status !== Tour::STATUS_PENDING_REVIEW) {
            return;
        }

        if ($action === 'updated' && $oldStatus === Tour::STATUS_PENDING_REVIEW) {
            return;
        }

        $actor = auth('admins')->user();
        $actorName = $actor ? $actor->name : 'Nhân viên';

        CreateAppNotification::dispatch([
            'receiver_guard' => 'admins',
            'type' => 'tour_pending_review',
            'title' => 'Có tour chờ duyệt',
            'message' => $actorName . ' vừa ' . ($action === 'created' ? 'tạo' : 'cập nhật') . ' tour "' . $tour->t_title . '".',
            'url' => route('tour.index', ['t_status' => Tour::STATUS_PENDING_REVIEW], false),
            'data' => [
                'tour_id' => $tour->id,
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
                'action' => $action,
                'actor_id' => $actor ? $actor->id : null,
            ],
        ]);
    }
}
