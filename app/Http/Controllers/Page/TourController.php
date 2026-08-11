<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Location;
use App\Models\User;
use App\Http\Requests\BookTourRequest;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Mail;

class TourController extends Controller
{
    private const TOUR_PER_PAGE = 8;

    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    //
    public function index(Request $request)
    {
        $tours = Tour::with(['user', 'location']);

        if ($request->key_tour) {
            $tours->where('t_title', 'like', '%' . $request->key_tour . '%');
        }

        if ($request->location_id) {
            $tours->where('t_location_id', $request->location_id);
        }

        if ($request->price && preg_match('/^\d+-\d+$/', $request->price)) {
            $price = explode('-', $request->price);
            $tours->whereBetween('t_price_adults', [$price[0], $price[1]]);
        }

        if ($request->duration) {
            $this->applyDurationFilter($tours, $request->duration);
        }

        $tours = $tours->orderBy('t_status')
            ->visibleToCustomers()
            ->paginate(self::TOUR_PER_PAGE)
            ->appends($request->query());

        $locations = Location::active()->get();

        $viewData = [
            'tours' => $tours,
            'locations' => $locations,
        ];
        return view('page.tour.index', $viewData);
    }

    private function applyDurationFilter($query, string $duration): void
    {
        if ($duration === '1') {
            $query->where('t_duration_days', 1);
            return;
        }

        if ($duration === '2-3') {
            $query->whereBetween('t_duration_days', [2, 3]);
            return;
        }

        if ($duration === '4-5') {
            $query->whereBetween('t_duration_days', [4, 5]);
            return;
        }

        if ($duration === '6+') {
            $query->where('t_duration_days', '>=', 6);
        }
    }

    public function detail(Request $request, $id)
    {
        $tour = Tour::with(['comments' => function ($query) use ($id) {
            $query->with(['user', 'replies' => function ($q) {
                $q->with('user')->limit(10);
            }])
                ->where('cm_tour_id', $id)
                ->where('cm_status', '!=', 3) // Ẩn những BL admin đã ẩn (status=3)
                ->limit(20)
                ->orderByDesc('id');
        }])->visibleToCustomers()->find($id);

        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $tours = Tour::where('t_location_id', $tour->t_location_id)
            ->where('id', '<>', $id)
            ->visibleToCustomers()
            ->orderBy('id')
            ->limit(NUMBER_PAGINATION_PAGE)
            ->get();

        return view('page.tour.detail', compact('tour', 'tours'));
    }

    public function activities(Request $request, $id, $slug)
    {
        $tour = Tour::with('location')->visibleToCustomers()->find($id);

        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $relatedTours = Tour::where('t_location_id', $tour->t_location_id)
            ->where('id', '<>', $id)
            ->visibleToCustomers()
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('page.tour.activities', compact('tour', 'relatedTours'));
    }

    public function bookTour(Request $request, $id, $slug)
    {
        if (!Auth::guard('users')->check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đặt tour');
        }
        $tour = Tour::find($id);

        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }
        if ((int) $tour->t_status !== 1) {
            return redirect()->back()->with('error', 'Tour hiện không còn nhận đặt chỗ');
        }
        $user =  User::find(Auth::guard('users')->user()->id);

        return view('page.tour.book', compact('tour', 'user'));
    }

    public function postBookTour(BookTourRequest $request, $id)
    {
        if (!Auth::guard('users')->check()) {
            return redirect()->route('page.user.account')->with('error', 'Vui lòng đăng nhập để đặt tour');
        }

        try {
            $params = $request->except(['_token']);
            $user = Auth::guard('users')->user();
            $bookingData = $this->bookingService->createForTour((int) $id, $user, $params);

            // Gửi email sau khi transaction đã commit; lỗi mail không rollback DB.
            try {
                $book = $bookingData['book'];
                $tour = $bookingData['tour'];
                $mail = $bookingData['user']->email;
                Mail::send('emailtn', compact('book', 'tour', 'user'), function ($email) use ($mail) {
                    $email->subject('Miu Travel đã tiếp nhận yêu cầu đặt tour của quý khách');
                    $email->to($mail);
                });
            } catch (\Exception $mailException) {
                // Không rollback DB khi mail lỗi.
            }

            return redirect()->route('page.home')->with('success', 'Cám ơn bạn đã đặt tour chúng tôi sẽ liên hệ sớm để xác nhận.');
        } catch (\DomainException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu dữ liệu');
        }
    }
}
