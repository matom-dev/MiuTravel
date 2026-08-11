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

  $month = $request->select_month ? $request->select_month : date('m');
  $year = $request->select_year ? $request->select_year : date('Y');
  $listDay = Date::getListDayInMonth($month, $year);

  //Thống kê số lượng người lớn hàng đặt tour
  $revenueTransactionMonth = BookTour::whereMonth('created_at', $month)->whereYear('created_at', $year)
      ->select(\DB::raw('sum(b_number_adults) as totalMoney'), \DB::raw('DATE(created_at) day'))
      ->groupBy('day')
      ->get()->toArray();

  // Thống kê khối lượng trẻ em đặt tour
  $revenueTransactionMonthDefault = BookTour::whereMonth('created_at', $month)->whereYear('created_at', $year)
      ->select(\DB::raw('(sum(b_number_children)+sum(b_number_child6)+sum(b_number_child2)) as totalMoney'), \DB::raw('DATE(created_at) day'))
      ->groupBy('day')
      ->get()->toArray();
  //thống kê doanh thu
  $money = BookTour::whereIn('b_status', [3, 4])->whereMonth('created_at', $month)->whereYear('created_at', $year)
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
  $publishedHotels = Hotel::where('h_status', 1)->count();
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

  $topBookedTours = BookTour::with('tour')
      ->whereIn('b_status', [1, 2, 3, 4])
      ->select('b_tour_id')
      ->selectRaw('COUNT(*) as bookings_count')
      ->selectRaw('SUM(' . $guestExpression . ') as guests_count')
      ->selectRaw('SUM(' . $revenueExpression . ') as revenue_total')
      ->groupBy('b_tour_id')
      ->orderByDesc('bookings_count')
      ->limit(5)
      ->get();

  $monthlyLabels = [];
  $monthlyBookingCounts = array_fill(1, 12, 0);
  $monthlyRevenueTotals = array_fill(1, 12, 0);
  for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++) {
      $monthlyLabels[] = 'T' . $monthIndex;
  }

  $monthlyBookings = BookTour::whereYear('created_at', $year)
      ->select(\DB::raw('MONTH(created_at) as month_number'), \DB::raw('COUNT(*) as total_bookings'))
      ->groupBy('month_number')
      ->get();
  foreach ($monthlyBookings as $monthlyBooking) {
      $monthlyBookingCounts[(int) $monthlyBooking->month_number] = (int) $monthlyBooking->total_bookings;
  }

  $monthlyRevenue = BookTour::whereYear('created_at', $year)
      ->whereIn('b_status', [1, 2, 3, 4])
      ->select(\DB::raw('MONTH(created_at) as month_number'))
      ->selectRaw('SUM(' . $revenueExpression . ') as total_revenue')
      ->groupBy('month_number')
      ->get();
  foreach ($monthlyRevenue as $monthlyRevenueItem) {
      $monthlyRevenueTotals[(int) $monthlyRevenueItem->month_number] = (int) $monthlyRevenueItem->total_revenue;
  }

  $latestBookings = BookTour::with(['tour', 'user'])->orderByDesc('id')->limit(5)->get();
  $upcomingTours = Tour::where('t_status', 1)
      ->orderByDesc('id')
      ->limit(5)
      ->get();
  $lowSeatTours = Tour::where('t_status', 1)
      ->orderByDesc('t_follow')
      ->limit(5)
      ->get();
  $latestComments = Comment::with(['user', 'article', 'tour', 'hotel'])->orderByDesc('id')->limit(4)->get();

  $tours = Tour::orderByDesc('t_follow')->limit(3)->get();
  $viewData = [
      'user' => $user,
      'article' => $article,
      'bookTour' => $bookTour,
      'tour' => $tour,
      'hotel' => $hotel,
      'comment' => $comment,
      'tours' => $tours,
      'pendingBookings' => $pendingBookings,
      'bookingAwaitingConfirmation' => $bookingAwaitingConfirmation,
      'confirmedBookings' => $confirmedBookings,
      'cancelledBookings' => $cancelledBookings,
      'expectedRevenue' => $expectedRevenue,
      'topBookedTours' => $topBookedTours,
      'publishedTours' => $publishedTours,
      'publishedHotels' => $publishedHotels,
      'hiddenComments' => $hiddenComments,
      'newBookingsToday' => $newBookingsToday,
      'bookingStatusCounts' => $bookingStatusCounts,
      'bookingCompletionRate' => $bookingCompletionRate,
      'latestBookings' => $latestBookings,
      'upcomingTours' => $upcomingTours,
      'lowSeatTours' => $lowSeatTours,
      'latestComments' => $latestComments,
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
        $month = $request->select_month ? (int) $request->select_month : (int) date('m');
        $year = $request->select_year ? (int) $request->select_year : (int) date('Y');
        $revenueExpression = '(COALESCE(b_price_adults,0) * COALESCE(b_number_adults,0))'
            . ' + (COALESCE(b_price_children,0) * COALESCE(b_number_children,0))'
            . ' + (COALESCE(b_price_child6,0) * COALESCE(b_number_child6,0))'
            . ' + (COALESCE(b_price_child2,0) * COALESCE(b_number_child2,0))';

        $revenueBaseQuery = BookTour::whereIn('b_status', [3, 4])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $totalRevenueMonth = (clone $revenueBaseQuery)
            ->select(\DB::raw('SUM(' . $revenueExpression . ') as total_revenue'))
            ->value('total_revenue') ?? 0;

        $revenueBookings = $revenueBaseQuery
            ->with(['tour', 'user'])
            ->select('book_tours.*')
            ->selectRaw($revenueExpression . ' as revenue_total')
            ->orderByDesc('created_at')
            ->paginate(NUMBER_PAGINATION_PAGE);

        return view('admin.home.revenue_month', compact(
            'month',
            'year',
            'totalRevenueMonth',
            'revenueBookings'
        ));
    }

    public function bookingOverview(Request $request)
    {
        $bookings = BookTour::with(['tour', 'user', 'schedule']);

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
}
