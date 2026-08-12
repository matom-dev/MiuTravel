<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Location;
use App\Models\TourGuide;
use App\Http\Requests\TourRequest;
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
        $tours = Tour::with('location');
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
        return view('admin.tour.index', compact('tours'));
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
            $this->auditLogger->log('tour.created', $tour, [
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
            ], $request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
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
            $tour = $this->tour->createOrUpdate($request, $id);
            $this->auditLogger->log('tour.updated', $tour, [
                'title' => $tour->t_title,
                'status' => (int) $tour->t_status,
            ], $request);
            \DB::commit();
            return redirect()->back()->with('success', 'Lưu dữ liệu thành công');
        } catch (\Exception $exception) {
            \DB::rollBack();
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
}
