<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Tour;
use App\Models\Article;
use App\Models\CarRental;
use App\Models\Comment;
use App\Models\Hotel;
use Illuminate\Support\Facades\Validator;
use Mail;

class HomeController extends Controller
{
    private const HOME_TOURS_PER_PAGE = 8;

    //
    public function index()
    {
        $locations = Location::with('tours')->active()->get();
        $articles = Article::with('category')
            ->active()
            ->whereHas('category', function ($query) {
                $query->where('c_slug', 'tin-tuc')
                    ->where('c_status', 1);
            })
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        $perPage = self::HOME_TOURS_PER_PAGE;
        $tours = Tour::with('location')
            ->visibleToCustomers()
            ->orderByDesc('id')
            ->paginate($perPage);

        $totalPages = $tours->lastPage();
        $featuredHotels = Hotel::with('location')
            ->active()
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        // Lấy tất cả bình luận được admin duyệt nổi bật (cm_status = 2)
        $comments = Comment::with(['user', 'article', 'tour', 'hotel'])
            ->where('cm_status', 2)
            ->whereNotNull('cm_user_id')
            ->orderByDesc('id')
            ->get();
        $viewData = [
            'locations'  => $locations,
            'articles'   => $articles,
            'tours'      => $tours,
            'featuredHotels' => $featuredHotels,
            'comments'   => $comments,
            'totalPages' => $totalPages,
            'perPage'    => $perPage,
        ];
        return view('page.home.index', $viewData);
    }

    /**
     * AJAX: Lấy danh sách tours theo trang cho trang chủ
     */
    public function getToursAjax(Request $request)
    {
        $perPage = self::HOME_TOURS_PER_PAGE;
        $page    = max(1, (int) $request->get('page', 1));

        $tours = Tour::with('location')
            ->visibleToCustomers()
            ->orderByDesc('id')
            ->paginate($perPage, ['*'], 'page', $page);

        $html = '';
        foreach ($tours as $tour) {
            $html .= view('page.common.itemTour', [
                'tour' => $tour,
                'showTourIntro' => true,
            ])->render();
        }

        return response()->json([
            'html'        => $html,
            'total'       => $tours->total(),
            'currentPage' => $tours->currentPage(),
            'lastPage'    => $tours->lastPage(),
        ]);
    }


    public function contact()
    {
        return redirect()->to(route('about.us') . '#lien-he');
    }

    public function sendContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email|max:100',
            'message' => 'required|string|max:2000',
        ], [
            'name.required'    => 'Vui lòng nhập họ và tên.',
            'phone.required'   => 'Vui lòng nhập số điện thoại.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        if ($validator->fails()) {
            return redirect()->to(route('about.us') . '#lien-he')
                ->withErrors($validator)
                ->withInput();
        }

        // TODO: Gửi email thông báo nếu cần
        // Mail::to('letoantrung73@gmail.com')->send(new \App\Mail\ContactMail($request->all()));

        return redirect()->to(route('about.us') . '#lien-he')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong vòng 24 giờ.');
    }

    public function about()
    {
        $comments = Comment::with('user')->where('cm_status', 2)->limit(10)->get();
        return view('page.about.index', compact('comments'));
    }

    public function serviceHub()
    {
        $hotels = Hotel::active()->orderByDesc('id')->limit(3)->get();
        $carRentals = CarRental::active()->orderByDesc('id')->limit(3)->get();

        return view('page.service.index', compact('hotels', 'carRentals'));
    }

}
