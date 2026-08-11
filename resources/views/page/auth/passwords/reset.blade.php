@extends('page.layouts.page')
@section('title', 'Đặt lại mật khẩu | Miu Travel')
@section('style')
<style>
.pw-wrap {
    background: #f7f3ef;
    padding: 70px 0;
    min-height: calc(100vh - 260px);
}
.pw-card {
    max-width: 600px;
    margin: 0 auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 16px 44px rgba(0,0,0,.08);
    overflow: hidden;
}
.pw-head {
    padding: 30px 34px;
    background: linear-gradient(135deg, #f15d30, #e04820);
    color: #fff;
}
.pw-head h2 {
    margin: 0 0 8px;
    font-family: 'Playfair Display', serif;
    font-size: 1.55rem;
    font-weight: 800;
}
.pw-head p {
    margin: 0;
    color: rgba(255,255,255,.78);
    font-size: 14px;
    line-height: 1.7;
}
.pw-body { padding: 34px; }
.pw-field { margin-bottom: 18px; }
.pw-field label {
    display: block;
    margin-bottom: 8px;
    color: #3d3a38;
    font-size: 13px;
    font-weight: 700;
}
.pw-input { position: relative; }
.pw-input i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #c4c0bb;
}
.pw-input input {
    width: 100%;
    height: 50px;
    padding: 0 16px 0 44px;
    border: 1.5px solid #ebe8e5;
    border-radius: 11px;
    background: #faf9f8;
    color: #1a1a1a;
    font-size: 14.5px;
    outline: none;
}
.pw-input input:focus {
    border-color: #f15d30;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(241,93,48,.1);
}
.pw-input input.err { border-color: #fca5a5; background: #fff8f7; }
.pw-err {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 7px;
    color: #ef4444;
    font-size: 12px;
    font-weight: 600;
}
.pw-submit {
    width: 100%;
    height: 52px;
    margin-top: 6px;
    border: 0;
    border-radius: 12px;
    background: linear-gradient(135deg, #f97040, #f15d30 55%, #e04820);
    color: #fff;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 16px rgba(241,93,48,.28);
}
.pw-back {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    font-size: 14px;
}
.pw-back a {
    color: #f15d30;
    font-weight: 700;
    text-decoration: none;
}
.pw-back a:hover { color: #d9461f; text-decoration: none; }
@media (max-width: 576px) {
    .pw-wrap { padding: 36px 12px; }
    .pw-head, .pw-body { padding: 26px 22px; }
}
</style>
@stop

@section('content')
<section class="hero-wrap hero-wrap-2" style="background: #ffffff; background-image: none;">
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start" style="min-height:200px;padding-bottom:30px;">
            <div class="col-md-9 ftco-animate pb-5 text-left">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                    <span>Đặt lại mật khẩu <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Đặt lại mật khẩu</h1>
            </div>
        </div>
    </div>
</section>

<div class="pw-wrap">
    <div class="container">
        <div class="pw-card">
            <div class="pw-head">
                <h2>Tạo mật khẩu mới</h2>
                <p>Vui lòng nhập email và mật khẩu mới để tiếp tục sử dụng tài khoản.</p>
            </div>
            <div class="pw-body">
                <form method="POST" action="{{ route('page.user.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="pw-field">
                        <label for="reset-email">Địa chỉ Email <sup>*</sup></label>
                        <div class="pw-input">
                            <i class="fa fa-envelope"></i>
                            <input
                                id="reset-email"
                                type="email"
                                name="email"
                                value="{{ $email ?? old('email') }}"
                                placeholder="vd: nguyenvana@gmail.com"
                                autocomplete="email"
                                class="{{ $errors->has('email') ? 'err' : '' }}"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="pw-err"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pw-field">
                        <label for="reset-password">Mật khẩu mới <sup>*</sup></label>
                        <div class="pw-input">
                            <i class="fa fa-lock"></i>
                            <input
                                id="reset-password"
                                type="password"
                                name="password"
                                placeholder="Nhập mật khẩu mới"
                                autocomplete="new-password"
                                class="{{ $errors->has('password') ? 'err' : '' }}"
                                required
                            >
                        </div>
                        @error('password')
                            <div class="pw-err"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pw-field">
                        <label for="reset-password-confirm">Xác nhận mật khẩu <sup>*</sup></label>
                        <div class="pw-input">
                            <i class="fa fa-check-circle"></i>
                            <input
                                id="reset-password-confirm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Nhập lại mật khẩu mới"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="pw-submit">
                        <i class="fa fa-refresh"></i> Đặt lại mật khẩu
                    </button>
                </form>

                <div class="pw-back">
                    <a href="{{ route('page.user.account') }}"><i class="fa fa-arrow-left"></i> Quay lại đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
