<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Location;
use App\Models\User;
use App\Models\BookTour;
use App\Models\Comment;
use App\Http\Requests\BookTourRequest;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Mail;

class TourController extends Controller
{
    private const TOUR_PER_PAGE = 16;

    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    //
    public function index(Request $request)
    {
        $validated = $request->validate([
            'key_tour' => 'nullable|string|max:191',
            'location_id' => 'nullable|integer',
            'price' => 'nullable|regex:/^\d+-\d+$/',
            'duration' => 'nullable|in:1,2-3,4-5,6+',
            'tour_type' => ['nullable', 'string', Rule::in(array_keys(Tour::TOUR_TYPES))],
        ]);

        $tours = Tour::with(['user', 'location']);

        if (!empty($validated['key_tour'])) {
            $tours->where('t_title', 'like', '%' . $validated['key_tour'] . '%');
        }

        if (!empty($validated['location_id'])) {
            $tours->where('t_location_id', $validated['location_id']);
        }

        if (!empty($validated['price'])) {
            $price = explode('-', $validated['price']);
            $tours->whereBetween('t_price_adults', [$price[0], $price[1]]);
        }

        if (!empty($validated['duration'])) {
            $this->applyDurationFilter($tours, $validated['duration']);
        }

        if (!empty($validated['tour_type'])) {
            $tours->where('t_type', $validated['tour_type']);
        }

        $tours = $tours->orderBy('t_status')
            ->orderByDesc('id')
            ->visibleToCustomers()
            ->paginate(self::TOUR_PER_PAGE)
            ->appends($request->query());

        $locations = Location::active()->get();

        $viewData = [
            'tours' => $tours,
            'locations' => $locations,
            'tourTypes' => Tour::TOUR_TYPES,
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
                $q->with('user')->where('cm_status', Comment::STATUS_APPROVED)->limit(10);
            }])
                ->where('cm_tour_id', $id)
                ->where('cm_status', Comment::STATUS_APPROVED)
                ->limit(20)
                ->orderByDesc('id');
        }])->visibleToCustomers()->find($id);

        if (!$tour) {
            return redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        $tours = Tour::where('id', '<>', $id)
            ->visibleToCustomers();

        if ($tour->t_location_id || $tour->t_type) {
            $tours->where(function ($query) use ($tour) {
                if ($tour->t_location_id) {
                    $query->where('t_location_id', $tour->t_location_id);
                }

                if ($tour->t_type) {
                    $tour->t_location_id
                        ? $query->orWhere('t_type', $tour->t_type)
                        : $query->where('t_type', $tour->t_type);
                }
            });
        }

        $tours = $tours->withCount(['booktour as booking_count' => function ($query) {
                $query->whereIn('b_status', [
                    BookTour::STATUS_CONFIRMED,
                    BookTour::STATUS_PAID,
                    BookTour::STATUS_COMPLETED,
                ]);
            }])
            ->orderByDesc('booking_count')
            ->orderByDesc('id')
            ->limit(NUMBER_PAGINATION_PAGE)
            ->get();

        $itineraryDays = $this->buildItineraryDays($tour);
        $canReviewTour = $this->canCurrentUserReviewTour($tour->id);

        return view('page.tour.detail', compact('tour', 'tours', 'itineraryDays', 'canReviewTour'));
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

    private function canCurrentUserReviewTour(int $tourId): bool
    {
        $userId = Auth::guard('users')->id();

        if (!$userId) {
            return false;
        }

        return BookTour::where('b_user_id', $userId)
            ->where('b_tour_id', $tourId)
            ->whereIn('b_status', [
                BookTour::STATUS_CONFIRMED,
                BookTour::STATUS_PAID,
                BookTour::STATUS_COMPLETED,
            ])
            ->exists();
    }

    private function buildItineraryDays(Tour $tour): array
    {
        $html = trim((string) $tour->t_description);

        if ($html === '') {
            return [];
        }

        $pattern = '/(<h[2-4][^>]*>\\s*(?:ngày|day)\\s*\\d+[^<]*<\\/h[2-4]>)/iu';
        $parts = preg_split($pattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        if (count($parts) < 2) {
            return [];
        }

        $days = [];
        for ($i = 0; $i < count($parts); $i++) {
            if (!preg_match($pattern, $parts[$i])) {
                continue;
            }

            $title = trim(strip_tags($parts[$i]));
            $content = trim($parts[$i + 1] ?? '');

            if ($title !== '' && $content !== '') {
                $days[] = [
                    'title' => $title,
                    'content' => $content,
                ];
            }
        }

        return $days;
    }
}
