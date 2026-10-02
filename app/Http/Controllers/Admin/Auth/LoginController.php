<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('guest:admins')->except('logout');
        $this->user = $user;
    }

    public function login()
    {
        if (Auth::guard('admins')->check()) {
            return redirect()->route(Auth::guard('admins')->user()->can('quan-ly-dai-ly') ? 'agency.dashboard' : 'admin.home');
        }

        return view('admin.auth.login');
    }

    /**
     * Xử lý thực hiện đăng nhập trang admin
     *
     * @return RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = $this->user->getInfoEmail($email);

        if (! $user) {
            return redirect()->back()->with('danger', 'Thông tin tài khoản không tồn tại');
        }

        if (Auth::guard('admins')->attempt(['email' => $email, 'password' => $password])) {
            $admin = Auth::guard('admins')->user();

            if (! $admin->can(['truy-cap-he-thong', 'full-quyen-quan-ly'])) {
                Auth::guard('admins')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('admin.login')->with('danger', 'Tài khoản không có quyền truy cập trang quản trị.');
            }

            return redirect()->route($admin->can('quan-ly-dai-ly') && ! $admin->can(['xem-dashboard', 'full-quyen-quan-ly'])
                ? 'agency.dashboard' : 'admin.home');
        }

        return redirect()->back()->with('danger', 'Đăng nhập thất bại. Vui lòng kiểm tra lại mật khẩu.');
    }

    public function logout()
    {
        Auth::guard('admins')->logout();

        return redirect()->route('admin.login');
    }
}
