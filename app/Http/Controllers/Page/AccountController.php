<?php

namespace App\Http\Controllers\Page;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateInfoAccountRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Models\BookTour;
use App\Services\BookingDocumentService;
use App\Services\BookingService;
use Mail;

class AccountController extends Controller
{
    protected $bookingService;

    public function __construct(BookTour $bookTour, BookingService $bookingService)
    {
        view()->share([
            'status' => $bookTour::STATUS,
            'classStatus' => $bookTour::CLASS_STATUS,
        ]);

        $this->bookingService = $bookingService;
    }
    //
    public function infoAccount()
    {
        $user = Auth::guard('users')->user();
        return view('page.auth.account', compact('user'));
    }

    public function updateInfoAccount(UpdateInfoAccountRequest $request)
    {
        \DB::beginTransaction();
        try {
            $user =  User::find(Auth::guard('users')->user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;

            if ($request->hasFile('images')) {
                $image = upload_image('images');
                if ($image['code'] == 1)
                    $user->avatar = $image['name'];
            }

            $user->save();
            \DB::commit();
            return redirect()->back()->with('success', 'Cập nhật thành công.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể cập nhật tài khoản');
        }
    }

    public function changePassword()
    {
        $user = Auth::guard('users')->user();
        return view('page.auth.change_password', compact('user'));
    }

    public function postChangePassword(ChangePasswordRequest $request)
    {
        \DB::beginTransaction();
        try {
            $user =  User::find(Auth::guard('users')->user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
            \DB::commit();
            Auth::guard('users')->logout();
            return redirect()->route('page.user.account')->with('success', 'Đổi mật khẩu thành công.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi không thể đổi mật khẩu');
        }
    }




    public function myTour()
    {
        $user = Auth::guard('users')->user();
        $bookTours = BookTour::with([
            'tour',
            'schedule',
        ])->where('b_user_id', $user->id)->orderByDesc('id')->paginate(NUMBER_PAGINATION_PAGE);
        return view('page.auth.my_tour', compact('bookTours', 'user'));
    }

    public function downloadBookingConfirmation($id, BookingDocumentService $documents)
    {
        $user = Auth::guard('users')->user();
        $bookTour = BookTour::with(['tour', 'user'])
            ->where('b_user_id', $user->id)
            ->findOrFail($id);

        return $documents->confirmation($bookTour);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('users')->user();
        $bookTour = BookTour::where('id', $id)->where('b_user_id', $user->id)->first();

        if (!$bookTour) {
            return $request->ajax()
                ? response(['status_code' => 404, 'message' => 'Dữ liệu không tồn tại'])
                : redirect()->back()->with('error', 'Dữ liệu không tồn tại');
        }

        if ((int) $bookTour->b_status !== 1) {
            return $request->ajax()
                ? response(['status_code' => 400, 'message' => 'Chỉ có thể hủy booking đang chờ xác nhận'])
                : redirect()->back()->with('error', 'Chỉ có thể hủy booking đang chờ xác nhận');
        }

        $data = $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        try {
            $result = $this->bookingService->changeStatus($bookTour, BookTour::STATUS_CANCELLED, $data['cancel_reason'], $user, 'users');

            $mailuser = $user->email;
            try {
                $bookTour = $result['bookTour'];
                $tour = $result['tour'];
                Mail::send('emailhuy', compact('user', 'bookTour', 'tour'), function ($email) use ($mailuser) {
                    $email->subject('Xác nhận HUỶ BOOKING');
                    $email->to($mailuser);
                });
            } catch (\Exception $mailException) {
                // Lỗi mail không nên rollback DB, chỉ bỏ qua
            }

            return $request->ajax()
                ? response(['status_code' => 200, 'message' => 'Hủy thành công đơn hàng'])
                : redirect()->back()->with('success', 'Hủy thành công đơn hàng');
        } catch (\DomainException $exception) {
            return $request->ajax()
                ? response(['status_code' => 400, 'message' => $exception->getMessage()])
                : redirect()->back()->with('error', $exception->getMessage());
        } catch (\Exception $exception) {
            return $request->ajax()
                ? response(['status_code' => 500, 'message' => 'Không thể hủy đơn hàng'])
                : redirect()->back()->with('error', 'Không thể hủy đơn hàng');
        }
    }
}
