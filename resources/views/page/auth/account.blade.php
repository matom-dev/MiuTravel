@extends('page.layouts.page')
@section('title', 'Thông tin tài khoản | Miu Travel')
@section('style')
<style>
    /* ═══════════════════════════════════════════════
   TRANG TÀI KHOẢN — LIGHT & ELEGANT
═══════════════════════════════════════════════ */
    .account-wrap {
        min-height: calc(100vh - 260px);
        background: #f7f3ef;
        padding: 56px 0 72px;
        font-family: 'Inter', sans-serif;
    }

    /* ── Shared layout ── */
    .account-layout {
        display: flex;
        gap: 28px;
        align-items: flex-start;
    }

    /* ═══════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════ */
    .user-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .04),
            0 8px 24px rgba(0, 0, 0, .07);
        position: sticky;
        top: 24px;
    }

    /* Profile block */
    .us-profile {
        background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
        padding: 28px 20px 24px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .us-profile::before {
        content: '';
        position: absolute;
        width: 180px;
        height: 180px;
        top: -70px;
        right: -50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .1);
        pointer-events: none;
    }

    .us-avatar {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, .5);
        overflow: hidden;
        margin: 0 auto 12px;
        background: #fff;
    }

    .us-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .us-name {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 3px;
        position: relative;
        z-index: 1;
    }

    .us-email {
        font-size: 12px;
        color: rgba(255, 255, 255, .72);
        position: relative;
        z-index: 1;
        word-break: break-all;
    }

    /* Nav */
    .us-nav {
        padding: 10px 0;
    }

    .us-nav-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 13px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #57534e;
        text-decoration: none;
        transition: background .18s, color .18s, border-left .18s;
        border-left: 3px solid transparent;
        position: relative;
    }

    .us-nav-item:hover {
        background: #fef6f0;
        color: #f15d30;
        border-left-color: #f15d30;
        text-decoration: none;
    }

    .us-nav-item.active {
        background: #fef3ee;
        color: #f15d30;
        font-weight: 600;
        border-left-color: #f15d30;
        text-decoration: none;
    }

    .us-nav-ic {
        width: 28px;
        height: 28px;
        background: #f5f0ec;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #a8a29e;
        flex-shrink: 0;
        transition: background .18s, color .18s;
    }

    .us-nav-item:hover .us-nav-ic,
    .us-nav-item.active .us-nav-ic {
        background: rgba(241, 93, 48, .12);
        color: #f15d30;
    }

    .us-nav-logout {
        border-top: 1px solid #f5f0ec;
        margin-top: 6px;
        color: #ef4444;
    }

    .us-nav-logout:hover {
        background: #fff5f5;
        color: #ef4444;
        border-left-color: #ef4444;
    }

    .us-nav-logout .us-nav-ic {
        background: #fee2e2;
        color: #ef4444;
    }

    /* ═══════════════════════════════════════════════
   MAIN CONTENT CARD
═══════════════════════════════════════════════ */
    .account-card {
        flex: 1;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .04),
            0 8px 24px rgba(0, 0, 0, .07);
    }

    .ac-head {
        padding: 24px 36px;
        border-bottom: 1px solid #f5f0ec;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ac-head-ic {
        width: 40px;
        height: 40px;
        background: rgba(241, 93, 48, .1);
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f15d30;
        font-size: 16px;
    }

    .ac-head h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0;
    }

    .ac-head p {
        font-size: 13px;
        color: #a8a29e;
        margin: 0;
    }

    .ac-body {
        padding: 36px;
    }

    /* ── Avatar upload section ── */
    .av-section {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 20px 24px;
        background: #faf9f8;
        border: 1.5px solid #ebe8e5;
        border-radius: 14px;
        margin-bottom: 28px;
    }

    .av-img {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        border: 3px solid #ebe8e5;
        overflow: hidden;
        flex-shrink: 0;
        background: #f0ece8;
        padding: 0;
        cursor: zoom-in;
    }

    .av-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .av-info h4 {
        font-size: 14px;
        font-weight: 700;
        color: #3d3a38;
        margin-bottom: 4px;
    }

    .av-info p {
        font-size: 12.5px;
        color: #a8a29e;
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .av-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: #fff;
        border: 1.5px solid #ebe8e5;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        color: #57534e;
        cursor: pointer;
        transition: all .18s;
    }

    .av-btn:hover {
        border-color: #f15d30;
        color: #f15d30;
        background: #fef6f0;
    }

    .av-file {
        display: none;
    }

    /* ── Section heading ── */
    .ac-sec {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #b0aaa4;
        margin-bottom: 16px;
    }

    .ac-sec i {
        color: #f15d30;
    }

    .ac-sec::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #ebe8e5;
    }

    /* ── Input group ── */
    .ac-group {
        margin-bottom: 20px;
    }

    .ac-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #3d3a38;
        margin-bottom: 8px;
    }

    .ac-group label sup {
        color: #f15d30;
    }

    .ac-field {
        position: relative;
    }

    .ac-field .ac-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #ccc9c6;
        pointer-events: none;
        transition: color .2s;
    }

    .ac-field:focus-within .ac-ico {
        color: #f15d30;
    }

    .ac-field input {
        width: 100%;
        height: 50px;
        border: 1.5px solid #ebe8e5;
        border-radius: 11px;
        padding: 0 16px 0 42px;
        font-size: 14.5px;
        font-family: 'Inter', sans-serif;
        color: #1a1a1a;
        background: #faf9f8;
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }

    .ac-field input::placeholder {
        color: #c4c0bb;
        font-size: 13.5px;
    }

    .ac-field input:focus {
        border-color: #f15d30;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(241, 93, 48, .1);
    }

    .ac-field input.err {
        border-color: #fca5a5;
        background: #fff8f7;
    }

    .ac-err {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 500;
        margin-top: 5px;
    }

    /* ── Alert ── */
    .ac-alert {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 12px 16px;
        border-radius: 11px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 24px;
    }

    .ac-alert-err {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .ac-alert-ok {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }

    /* ── Actions ── */
    .ac-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-top: 8px;
        margin-top: 8px;
        border-top: 1px solid #f5f0ec;
    }

    .ac-save {
        height: 50px;
        padding: 0 36px;
        background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        transition: transform .22s, box-shadow .22s;
        box-shadow: 0 4px 16px rgba(241, 93, 48, .28);
        position: relative;
        overflow: hidden;
    }

    .ac-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(241, 93, 48, .38);
    }

    .ac-save:active {
        transform: translateY(0);
    }

    .ac-save::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .15), transparent);
        transition: left .45s;
    }

    .ac-save:hover::after {
        left: 100%;
    }

    .ac-cancel {
        height: 50px;
        padding: 0 24px;
        background: #fff;
        border: 1.5px solid #ebe8e5;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        color: #78716c;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .2s;
        text-decoration: none;
    }

    .ac-cancel:hover {
        border-color: #d6d0cb;
        background: #faf9f8;
        color: #57534e;
        text-decoration: none;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .account-layout {
            flex-direction: column;
            gap: 20px;
        }

        .user-sidebar {
            width: 100%;
            position: static;
        }

        .us-nav {
            display: flex;
            flex-wrap: wrap;
            padding: 8px;
            gap: 4px;
        }

        .us-nav-item {
            border-left: none;
            border-radius: 8px;
            padding: 10px 14px;
            flex: 1;
            justify-content: center;
            min-width: 120px;
        }

        .us-nav-item.active,
        .us-nav-item:hover {
            border-left-color: transparent;
        }

        .ac-body {
            padding: 24px 20px;
        }

        .ac-head {
            padding: 20px 24px;
        }
    }
</style>
@stop

@section('content')
<section class="hero-wrap hero-wrap-2 account-page-hero"
    style="background: #ffffff; background-image: none;">
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start"
            style="min-height:200px;padding-bottom:30px;">
            <div class="col-md-9 ftco-animate pb-5 text-left">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i
                                class="fa fa-chevron-right"></i></a></span>
                    <span>Tài khoản <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Thông tin tài khoản</h1>
            </div>
        </div>
    </div>
</section>

<div class="account-wrap">
    <div class="container">
        <div class="account-layout">

            {{-- Sidebar --}}
            @include('page.common.sideBarUser')

            {{-- Main card --}}
            <div class="account-card" style="flex:1;">

                <div class="ac-head">
                    <div class="ac-head-ic"><i class="fa fa-user"></i></div>
                    <div>
                        <h2>Thông tin cá nhân</h2>
                        <p>Cập nhật họ tên, email, số điện thoại và địa chỉ của bạn</p>
                    </div>
                </div>

                <div class="ac-body">

                    @if ($errors->any())
                        <div class="ac-alert ac-alert-err">
                            <i class="fa fa-exclamation-circle"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="ac-alert ac-alert-ok">
                            <i class="fa fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('update.info.account') }}" method="POST" enctype="multipart/form-data"
                        id="accForm">
                        @csrf

                        {{-- Avatar --}}
                        <div class="av-section">
                            <button type="button" class="av-img" id="avatarPreview" data-avatar-viewer
                                aria-label="Xem ảnh đại diện">
                                <img src="{{ $user->avatar ? asset(pare_url_file($user->avatar)) : asset('page/images/user_default.png') }}"
                                    alt="Avatar" id="avatarImg">
                            </button>
                            <div class="av-info">
                                <h4>Ảnh đại diện</h4>
                                <p>Định dạng JPG, PNG, GIF. Dung lượng tối đa 2MB.<br>Kích thước tối ưu: 200×200 px.</p>
                                <button type="button" class="av-btn"
                                    onclick="document.getElementById('avatarFile').click()">
                                    <i class="fa fa-camera"></i> Thay đổi ảnh
                                </button>
                                <input type="file" id="avatarFile" name="images" class="av-file" accept="image/*"
                                    onchange="previewAvatar(event)">
                            </div>
                        </div>

                        {{-- Fields --}}
                        <div class="ac-sec"><i class="fa fa-id-card"></i> Thông tin cá nhân</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="acc-name">Họ và tên <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-user ac-ico"></i>
                                        <input type="text" id="acc-name" name="name" placeholder="Họ và tên đầy đủ"
                                            value="{{ old('name', $user->name) }}"
                                            class="{{ $errors->has('name') ? 'err' : '' }}">
                                    </div>
                                    @if ($errors->first('name'))
                                        <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="acc-phone">Số điện thoại <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-phone ac-ico"></i>
                                        <input type="tel" id="acc-phone" name="phone"
                                            placeholder="Số điện thoại liên hệ" value="{{ old('phone', $user->phone) }}"
                                            class="{{ $errors->has('phone') ? 'err' : '' }}">
                                    </div>
                                    @if ($errors->first('phone'))
                                        <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="acc-email">Email <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-envelope ac-ico"></i>
                                        <input type="email" id="acc-email" name="email" placeholder="Địa chỉ email"
                                            value="{{ old('email', $user->email) }}"
                                            class="{{ $errors->has('email') ? 'err' : '' }}">
                                    </div>
                                    @if ($errors->first('email'))
                                        <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="acc-address">Địa chỉ <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-map-marker ac-ico"></i>
                                        <input type="text" id="acc-address" name="address" placeholder="Địa chỉ của bạn"
                                            value="{{ old('address', $user->address) }}"
                                            class="{{ $errors->has('address') ? 'err' : '' }}">
                                    </div>
                                    @if ($errors->first('address'))
                                        <div class="ac-err"><i class="fa fa-times-circle"></i>
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="ac-actions">
                            <button type="submit" class="ac-save" id="accSaveBtn">
                                <i class="fa fa-save"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('page.home') }}" class="ac-cancel">
                                <i class="fa fa-times"></i> Hủy
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('script')
<script>
    function previewAvatar(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = evt => document.getElementById('avatarImg').src = evt.target.result;
        reader.readAsDataURL(file);
    }

    /* Ripple on save */
    const accSaveBtn = document.getElementById('accSaveBtn');
    if (accSaveBtn) {
        accSaveBtn.addEventListener('click', function (e) {
            const r = document.createElement('span');
            const rc = this.getBoundingClientRect();
            const sz = Math.max(this.clientWidth, this.clientHeight);
            r.style.cssText = `position:absolute;width:${sz}px;height:${sz}px;top:${e.clientY - rc.top - sz / 2}px;left:${e.clientX - rc.left - sz / 2}px;background:rgba(255,255,255,.22);border-radius:50%;transform:scale(0);animation:rA .5s ease-out;pointer-events:none;`;
            this.appendChild(r);
            if (!window.__rs) { window.__rs = 1; const s = document.createElement('style'); s.textContent = '@keyframes rA{to{transform:scale(2.5);opacity:0;}}'; document.head.appendChild(s); }
            setTimeout(() => r.remove(), 550);
        });
    }
</script>
@stop
