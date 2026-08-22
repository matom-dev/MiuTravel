<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Date;
use App\Models\Article;
use App\Models\User;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\Hotel;
use App\Models\Comment;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        view()->share([
            'home_active' => 'active',

        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
  //
  $user = User::count();
  $article = Article::count();
  $bookTour = BookTour::count();
  $tour = Tour::count();
  $hotel = Hotel::count();
  $comment = Comment::count();

  // Thống kê trạng thái đơn hàng
  // Tiep nhan
  $transactionDefault = BookTour::where('b_status',1)->select('id')->count();
  // Đã xác nhận
  $transactionProcess = BookTour::where('b_status',2)->select('id')->count();
  // Thành công
  $transactionSuccess = BookTour::where('b_status',3)->select('id')->count();
  // kết thúc
  $transactionFinish = BookTour::where('b_status',4)->select('id')->count();
  //Cancel
  $transactionCancel = BookTour::where('b_status',5)->select('id')->count();

  $bookingStatusCounts = [
      1 => $transactionDefault,
      2 => $transactionProcess,
      3 => $transactionSuccess,
      4 => $transactionFinish,
      5 => $transactionCancel,
  ];

  $statusTransaction = [
      [
          'Chờ xác nhận' , $transactionDefault, false
      ],
      [
          'Đã xác nhận' , $transactionProcess, false
      ],
      [
          'Đã thanh toán' , $transactionSuccess, false
      ],
      [
          'Hoàn tất' , $transactionFinish, false
      ],
      [
          'Đã hủy' , $transactionCancel, false
      ]
  ];

  $month = $this->normalizedMonth($request->input('select_month'));
  $year = $this->normalizedYear($request->input('select_year'));
  $chartDateFrom = \Carbon\Carbon::create((int) $year, (int) $month, 1)->startOfDay();
  $chartDateTo = (clone $chartDateFrom)->endOfMonth()->startOfDay();
  $listDay = Date::getListDayInMonth($month, $year);

  $applyDashboardDateScope = function ($query) use ($chartDateFrom, $chartDateTo) {
      return $query->whereDate('created_at', '>=', $chartDateFrom->toDateString())
          ->whereDate('created_at', '<=', $chartDateTo->toDateString());
  };

  //Thống kê số lượng người lớn hàng đặt tour
  $revenueTransactionMonth = $applyDashboardDateScope(BookTour::query())
      ->select(\DB::raw('sum(b_number_adults) as totalMoney'), \DB::raw('DATE(created_at) day'))
      ->groupBy('day')
      ->get()->toArray();

  // Thống kê khối lượng trẻ em đặt tour
  $revenueTransactionMonthDefault = $applyDashboardDateScope(BookTour::query())
      ->select(\DB::raw('(sum(b_number_children)+sum(b_number_child6)+sum(b_number_child2)) as totalMoney'), \DB::raw('DATE(created_at) day'))
      ->groupBy('day')
      ->get()->toArray();
  //thống kê doanh thu
  $money = $applyDashboardDateScope(BookTour::whereIn('b_status', [3, 4]))
  ->select(\DB::raw('(sum(b_price_adults*b_number_adults)+sum(b_price_children*b_number_children)+sum(b_price_child6*b_number_child6)+sum(b_price_child2*b_number_child2)) as totalMoney'), \DB::raw('DATE(created_at) day'))
  ->groupBy('day')
  ->get()->toArray();
  $arrmoney = [];
  $arrRevenueTransactionMonth = [];
  $arrRevenueTransactionMonthDefault = [];
  foreach($listDay as $day) {
      $total = 0;
      foreach ($revenueTransactionMonth as $key => $revenue) {
          if ($revenue['day'] ==  $day) {
              $total = $revenue['totalMoney'];
              break;
          }
      }

      $arrRevenueTransactionMonth[] = (int)$total;

      $total = 0;
      foreach ($revenueTransactionMonthDefault as $key => $revenue) {
          if ($revenue['day'] ==  $day) {
              $total = $revenue['totalMoney'];
              break;
          }
      }
      $arrRevenueTransactionMonthDefault[] = (int)$total;

      $total = 0;
      foreach ($money as $key => $revenue) {
          if ($revenue['day'] ==  $day) {
              $total = $revenue['totalMoney'];
              break;
          }
      }
      $arrmoney[] = (int)$total;
  }

  $totalRevenueMonth = array_sum($arrmoney);
  $totalGuestsMonth = array_sum($arrRevenueTransactionMonth) + array_sum($arrRevenueTransactionMonthDefault);
  $pendingBookings = $transactionDefault + $transactionProcess;
  $publishedTours = Tour::where('t_status', 1)->count();
  $pendingReviewTours = Tour::where('t_status', Tour::STATUS_PENDING_REVIEW)->count();
  $visibleHotels = Hotel::active()->count();
  $hiddenComments = Comment::where('cm_status', 3)->count();
  $newBookingsToday = BookTour::whereDate('created_at', date('Y-m-d'))->count();
  $bookingCompletionRate = $bookTour > 0 ? round(($transactionFinish / $bookTour) * 100) : 0;
  $bookingAwaitingConfirmation = $transactionDefault;
  $confirmedBookings = $transactionProcess;
  $cancelledBookings = $transactionCancel;
  $revenueExpression = '(COALESCE(b_price_adults,0) * COALESCE(b_number_adults,0))'
      . ' + (COALESCE(b_price_children,0) * COALESCE(b_number_children,0))'
      . ' + (COALESCE(b_price_child6,0) * COALESCE(b_number_child6,0))'
      . ' + (COALESCE(b_price_child2,0) * COALESCE(b_number_child2,0))';
  $guestExpression = 'COALESCE(b_number_adults,0) + COALESCE(b_number_children,0)'
      . ' + COALESCE(b_number_child6,0) + COALESCE(b_number_child2,0)';
  $expectedRevenue = BookTour::whereIn('b_status', [1, 2, 3, 4])
      ->select(\DB::raw('SUM(' . $revenueExpression . ') as total_revenue'))
      ->value('total_revenue') ?? 0;
  $revenueStatusScope = [3, 4];
  $quarterStart = now()->copy()->startOfQuarter();
  $quarterEnd = now()->copy()->endOfQuarter();
  $totalRevenueQuarter = BookTour::whereIn('b_status', $revenueStatusScope)
      ->whereBetween('created_at', [$quarterStart, $quarterEnd])
      ->select(\DB::raw('SUM(' . $revenueExpression . ') as total_revenue'))
      ->value('total_revenue') ?? 0;
  $totalRevenueYear = BookTour::whereIn('b_status', $revenueStatusScope)
      ->whereYear('created_at', date('Y'))
      ->select(\DB::raw('SUM(' . $revenueExpression . ') as total_revenue'))
      ->value('total_revenue') ?? 0;
  $bookingCancellationRate = $bookTour > 0 ? round(($transactionCancel / $bookTour) * 100, 1) : 0;
  $activeBookingStatuses = [
      BookTour::STATUS_PENDING,
      BookTour::STATUS_CONFIRMED,
      BookTour::STATUS_PAID,
      BookTour::STATUS_COMPLETED,
  ];
  $revenueBookingStatuses = [
      BookTour::STATUS_PAID,
      BookTour::STATUS_COMPLETED,
  ];
  $activeBookingCount = BookTour::whereIn('b_status', $activeBookingStatuses)->count();
  $confirmedOrBetterBookings = BookTour::whereIn('b_status', [
      BookTour::STATUS_CONFIRMED,
      BookTour::STATUS_PAID,
      BookTour::STATUS_COMPLETED,
  ])->count();
  $bookingConfirmationRate = $bookTour > 0 ? round(($confirmedOrBetterBookings / $bookTour) * 100, 1) : 0;
  $averageBookingValue = $activeBookingCount > 0 ? round($expectedRevenue / $activeBookingCount) : 0;
  $unassignedActiveBookingCount = BookTour::whereIn('b_status', [
      BookTour::STATUS_PENDING,
      BookTour::STATUS_CONFIRMED,
  ])
      ->whereNull('b_assigned_staff_id')
      ->count();
  $customersWithBookings = BookTour::whereNotNull('b_user_id')
      ->whereIn('b_status', $activeBookingStatuses)
      ->distinct('b_user_id')
      ->count('b_user_id');
  $returningCustomerCount = BookTour::whereNotNull('b_user_id')
      ->whereIn('b_status', $activeBookingStatuses)
      ->select('b_user_id')
      ->groupBy('b_user_id')
      ->havingRaw('COUNT(*) > 1')
      ->get()
      ->count();
  $returningCustomerRate = $customersWithBookings > 0 ? round(($returningCustomerCount / $customersWithBookings) * 100, 1) : 0;
  $upcomingBookingCount = BookTour::whereIn('b_status', [
      BookTour::STATUS_CONFIRMED,
      BookTour::STATUS_PAID,
  ])
      ->whereDate('b_start_date', '>=', now()->toDateString())
      ->whereDate('b_start_date', '<=', now()->addDays(3)->toDateString())
      ->count();

  $topBookedTours = $applyDashboardDateScope(BookTour::with('tour')
      ->whereIn('b_status', [1, 2, 3, 4]))
      ->select('b_tour_id')
      ->selectRaw('COUNT(*) as bookings_count')
      ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
      ->selectRaw('SUM(' . $revenueExpression . ') as revenue_total')
      ->groupBy('b_tour_id')
      ->orderByDesc('bookings_count')
      ->limit(5)
      ->get();

  $topRevenueTours = $applyDashboardDateScope(BookTour::with('tour')
      ->whereIn('b_status', $revenueBookingStatuses))
      ->select('b_tour_id')
      ->selectRaw('COUNT(*) as bookings_count')
      ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
      ->selectRaw('SUM(' . $revenueExpression . ') as revenue_total')
      ->groupBy('b_tour_id')
      ->orderByDesc('revenue_total')
      ->limit(5)
      ->get();

  $monthlyLabels = [];
  $monthlyBookingCounts = array_fill(1, 12, 0);
  $monthlyRevenueTotals = array_fill(1, 12, 0);
  $monthExpression = \DB::connection()->getDriverName() === 'sqlite'
      ? "CAST(strftime('%m', created_at) AS INTEGER)"
      : 'MONTH(created_at)';
  for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++) {
      $monthlyLabels[] = 'T' . $monthIndex;
  }

  $monthlyBookings = BookTour::whereYear('created_at', $year)
      ->select(\DB::raw($monthExpression . ' as month_number'), \DB::raw('COUNT(*) as total_bookings'))
      ->groupBy('month_number')
      ->get();
  foreach ($monthlyBookings as $monthlyBooking) {
      $monthlyBookingCounts[(int) $monthlyBooking->month_number] = (int) $monthlyBooking->total_bookings;
  }

  $monthlyRevenue = BookTour::whereYear('created_at', $year)
      ->whereIn('b_status', [1, 2, 3, 4])
      ->select(\DB::raw($monthExpression . ' as month_number'))
      ->selectRaw('SUM(' . $revenueExpression . ') as total_revenue')
      ->groupBy('month_number')
      ->get();
  foreach ($monthlyRevenue as $monthlyRevenueItem) {
      $monthlyRevenueTotals[(int) $monthlyRevenueItem->month_number] = (int) $monthlyRevenueItem->total_revenue;
  }

  $latestBookings = BookTour::with(['tour', 'user'])->orderByDesc('id')->limit(5)->get();
  $upcomingBookings = BookTour::with(['tour', 'user'])
      ->whereIn('b_status', [
          BookTour::STATUS_CONFIRMED,
          BookTour::STATUS_PAID,
      ])
      ->whereDate('b_start_date', '>=', now()->toDateString())
      ->whereDate('b_start_date', '<=', now()->addDays(3)->toDateString())
      ->orderBy('b_start_date')
      ->limit(5)
      ->get();
  $viewData = [
      'user' => $user,
      'article' => $article,
      'bookTour' => $bookTour,
      'tour' => $tour,
      'hotel' => $hotel,
      'comment' => $comment,
      'pendingBookings' => $pendingBookings,
      'bookingAwaitingConfirmation' => $bookingAwaitingConfirmation,
      'confirmedBookings' => $confirmedBookings,
      'cancelledBookings' => $cancelledBookings,
      'expectedRevenue' => $expectedRevenue,
      'averageBookingValue' => $averageBookingValue,
      'bookingConfirmationRate' => $bookingConfirmationRate,
      'totalRevenueQuarter' => $totalRevenueQuarter,
      'totalRevenueYear' => $totalRevenueYear,
      'bookingCancellationRate' => $bookingCancellationRate,
      'upcomingBookingCount' => $upcomingBookingCount,
      'unassignedActiveBookingCount' => $unassignedActiveBookingCount,
      'returningCustomerCount' => $returningCustomerCount,
      'returningCustomerRate' => $returningCustomerRate,
      'topBookedTours' => $topBookedTours,
      'topRevenueTours' => $topRevenueTours,
      'publishedTours' => $publishedTours,
      'pendingReviewTours' => $pendingReviewTours,
      'visibleHotels' => $visibleHotels,
      'hiddenComments' => $hiddenComments,
      'newBookingsToday' => $newBookingsToday,
      'bookingStatusCounts' => $bookingStatusCounts,
      'bookingCompletionRate' => $bookingCompletionRate,
      'latestBookings' => $latestBookings,
      'upcomingBookings' => $upcomingBookings,
      'totalRevenueMonth' => $totalRevenueMonth,
      'totalGuestsMonth' => $totalGuestsMonth,
      'statusTransaction'          => json_encode($statusTransaction),
      'listDay'                    => json_encode($listDay),
      'arrRevenueTransactionMonth' => json_encode($arrRevenueTransactionMonth),
      'arrRevenueTransactionMonthDefault' => json_encode($arrRevenueTransactionMonthDefault),
      'arrmoney' => json_encode($arrmoney),
      'monthlyLabels' => json_encode($monthlyLabels),
      'monthlyBookingCounts' => json_encode(array_values($monthlyBookingCounts)),
      'monthlyRevenueTotals' => json_encode(array_values($monthlyRevenueTotals)),
  ];
  return view('admin.home.index', $viewData);
    }

    public function revenueMonth(Request $request)
    {
        $month = $this->normalizedMonth($request->input('select_month'));
        $year = $this->normalizedYear($request->input('select_year'));
        $sort = $this->normalizedRevenueSort($request->input('sort'));
        $revenueExpression = '(COALESCE(b_price_adults,0) * COALESCE(b_number_adults,0))'
            . ' + (COALESCE(b_price_children,0) * COALESCE(b_number_children,0))'
            . ' + (COALESCE(b_price_child6,0) * COALESCE(b_number_child6,0))'
            . ' + (COALESCE(b_price_child2,0) * COALESCE(b_number_child2,0))';
        $guestExpression = 'COALESCE(b_number_adults,0) + COALESCE(b_number_children,0)'
            . ' + COALESCE(b_number_child6,0) + COALESCE(b_number_child2,0)';

        $revenueBaseQuery = BookTour::whereIn('b_status', [3, 4])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $totalRevenueMonth = (clone $revenueBaseQuery)
            ->select(\DB::raw('SUM(' . $revenueExpression . ') as total_revenue'))
            ->value('total_revenue') ?? 0;

        $totalPaidBookings = (clone $revenueBaseQuery)->count();
        $totalGuestsMonth = (clone $revenueBaseQuery)
            ->select(\DB::raw('SUM(' . $guestExpression . ') as total_guests'))
            ->value('total_guests') ?? 0;
        $totalRevenueTours = (clone $revenueBaseQuery)->distinct('b_tour_id')->count('b_tour_id');

        $tourRevenueQuery = (clone $revenueBaseQuery)
            ->with('tour')
            ->select('b_tour_id')
            ->selectRaw('COUNT(*) as bookings_count')
            ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
            ->selectRaw('SUM(' . $revenueExpression . ') as revenue_total')
            ->selectRaw('MAX(created_at) as latest_paid_at')
            ->groupBy('b_tour_id');

        switch ($sort) {
            case 'revenue_asc':
                $tourRevenueQuery->orderBy('revenue_total');
                break;
            case 'bookings_desc':
                $tourRevenueQuery->orderByDesc('bookings_count')->orderByDesc('revenue_total');
                break;
            case 'guests_desc':
                $tourRevenueQuery->orderByDesc('guests_count')->orderByDesc('revenue_total');
                break;
            case 'revenue_desc':
            default:
                $tourRevenueQuery->orderByDesc('revenue_total');
                break;
        }

        $tourRevenueReports = $tourRevenueQuery
            ->paginate(NUMBER_PAGINATION_PAGE);

        return view('admin.home.revenue_month', compact(
            'month',
            'year',
            'sort',
            'totalRevenueMonth',
            'totalPaidBookings',
            'totalGuestsMonth',
            'totalRevenueTours',
            'tourRevenueReports'
        ));
    }

    public function bookingOverview(Request $request)
    {
        $bookings = BookTour::with(['tour', 'user', 'schedule']);

        if ($request->boolean('upcoming')) {
            $bookings = $bookings->whereIn('b_status', [
                BookTour::STATUS_CONFIRMED,
                BookTour::STATUS_PAID,
            ])
                ->whereDate('b_start_date', '>=', now()->toDateString())
                ->whereDate('b_start_date', '<=', now()->addDays(3)->toDateString())
                ->orderBy('b_start_date')
                ->paginate(NUMBER_PAGINATION_PAGE);

            return view('admin.home.upcoming_bookings', [
                'bookings' => $bookings,
                'status' => BookTour::STATUS,
                'classStatus' => BookTour::CLASS_STATUS,
            ]);
        }

        if ($request->filled('b_status') && array_key_exists((int) $request->b_status, BookTour::STATUS)) {
            $bookings->where('b_status', (int) $request->b_status);
        }

        if ($request->filled('b_start_date')) {
            $bookings->whereDate('b_start_date', $request->b_start_date);
        }

        if ($request->filled('select_month')) {
            $bookings->whereMonth('b_start_date', (int) $request->select_month);
        }

        if ($request->filled('select_year')) {
            $bookings->whereYear('b_start_date', (int) $request->select_year);
        }

        $bookingCount = BookTour::count();
        $filteredBookingCount = (clone $bookings)->count();
        $filteredGuestCount = (clone $bookings)
            ->select(\DB::raw('SUM(COALESCE(b_number_adults,0) + COALESCE(b_number_children,0) + COALESCE(b_number_child6,0) + COALESCE(b_number_child2,0)) as total_guests'))
            ->value('total_guests') ?? 0;

        $bookings = $bookings->orderByDesc('created_at')->paginate(NUMBER_PAGINATION_PAGE);

        return view('admin.home.booking_overview', [
            'bookings' => $bookings,
            'bookingCount' => $bookingCount,
            'filteredBookingCount' => $filteredBookingCount,
            'filteredGuestCount' => $filteredGuestCount,
            'status' => BookTour::STATUS,
            'classStatus' => BookTour::CLASS_STATUS,
            'selectedStartDate' => $request->b_start_date,
            'selectedMonth' => $request->select_month,
            'selectedYear' => $request->select_year,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function normalizedMonth($value): int
    {
        $month = $this->integerInRange($value, 1, 12);

        return $month ?: (int) date('m');
    }

    private function normalizedYear($value): int
    {
        $year = $this->integerInRange($value, 2020, 2035);

        return $year ?: (int) date('Y');
    }

    private function normalizedRevenueSort($value): string
    {
        $allowedSorts = ['revenue_desc', 'revenue_asc', 'bookings_desc', 'guests_desc'];

        return in_array($value, $allowedSorts, true) ? $value : 'revenue_desc';
    }

    private function integerInRange($value, int $min, int $max): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '' || !ctype_digit($stringValue)) {
            return null;
        }

        $integerValue = (int) $stringValue;

        return $integerValue >= $min && $integerValue <= $max ? $integerValue : null;
    }
}
