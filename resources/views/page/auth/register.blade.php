@extends('page.layouts.page')
@section('title', 'Đăng ký tài khoản | Miu Travel')
@section('style')
<style>
    /* ═══════════════════════════════════════════════
   ĐĂNG KÝ — LIGHT & ELEGANT
═══════════════════════════════════════════════ */
    .reg-wrap {
        min-height: calc(100vh - 260px);
        background: #f7f3ef;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 64px 0;
        font-family: 'Inter', sans-serif;
    }

    .reg-wrap>.container {
        display: flex;
        justify-content: center;
        max-width: 1060px;
    }

    /* Card */
    .reg-card {
        width: 100%;
        max-width: 980px;
        margin: 0 auto;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .04),
            0 8px 24px rgba(0, 0, 0, .07),
            0 32px 64px rgba(0, 0, 0, .05);
        animation: fadeUp .45s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ─────────────── BANNER ─────────────── */
    .reg-banner {
        background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
        padding: 24px 40px;
        display: grid;
        grid-template-columns: minmax(180px, 1fr) auto minmax(180px, 1fr);
        align-items: center;
        gap: 18px;
        position: relative;
        overflow: hidden;
    }

    .reg-banner::before {
        content: '';
        position: absolute;
        width: 240px;
        height: 240px;
        top: -90px;
        right: -60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .1);
        pointer-events: none;
    }

    .reg-banner::after {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        bottom: -70px;
        left: 42%;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
        pointer-events: none;
    }

    /* Banner logo */
    .rb-logo {
        display: flex;
        align-items: center;
        gap: 11px;
        position: relative;
        z-index: 1;
        flex-shrink: 0;
    }

    .rb-logo-ic {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, .22);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .rb-logo-ic i {
        color: #fff;
        font-size: 16px;
    }

    .rb-logo-tx {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
    }

    .rb-logo-tx small {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 0;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .7);
        margin-top: 3px;
    }

    /* Banner steps */
    .rb-steps {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1;
        justify-content: center;
    }

    .rb-step {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, .55);
        white-space: nowrap;
    }

    .rb-step-n {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .18);
        border: 2px solid rgba(255, 255, 255, .28);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: rgba(255, 255, 255, .6);
    }

    .rb-step.on {
        color: #fff;
    }

    .rb-step.on .rb-step-n {
        background: rgba(255, 255, 255, .92);
        border-color: #fff;
        color: #f15d30;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
    }

    .rb-line {
        width: 32px;
        height: 2px;
        background: rgba(255, 255, 255, .22);
        margin: 0 7px;
    }

    /* Banner right text */
    .rb-tag {
        font-size: 13px;
        color: rgba(255, 255, 255, .68);
        line-height: 1.65;
        text-align: right;
        position: relative;
        z-index: 1;
        max-width: 230px;
        font-style: italic;
        justify-self: end;
    }

    /* ─────────────── FORM BODY ─────────────── */
    .reg-body {
        padding: 34px 42px 38px;
        background: #fff;
    }

    .rg-head {
        margin: 0 auto 10px;
        max-width: 520px;
        text-align: center;
    }

    .rg-head h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .rg-head p {
        font-size: 14px;
        color: #b0aaa4;
        margin: 0;
    }

    /* Section heading */
    .rg-sec {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0;
        color: #b0aaa4;
        margin: 24px 0 14px;
    }

    .rg-sec i {
        color: #f15d30;
    }

    .rg-sec::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #ebe8e5;
    }

    /* Input group */
    .rg-group {
        margin-bottom: 16px;
    }

    .rg-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #3d3a38;
        margin-bottom: 7px;
    }

    .rg-group label sup {
        color: #f15d30;
    }

    .rg-field {
        position: relative;
    }

    .rg-field .rg-ico {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        color: #ccc9c6;
        pointer-events: none;
        transition: color .2s;
    }

    .rg-field:focus-within .rg-ico {
        color: #f15d30;
    }

    .rg-field input {
        width: 100%;
        height: 48px;
        border: 1.5px solid #ebe8e5;
        border-radius: 11px;
        padding: 0 46px 0 42px;
        font-size: 14.5px;
        font-family: 'Inter', sans-serif;
        color: #1a1a1a;
        background: #faf9f8;
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }

    .rg-field input::placeholder {
        color: #c4c0bb;
        font-size: 13.5px;
    }

    .rg-field input:focus {
        border-color: #f15d30;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(241, 93, 48, .1);
    }

    .rg-field input.err {
        border-color: #fca5a5;
        background: #fff8f7;
    }

    .rg-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #c4c0bb;
        cursor: pointer;
        font-size: 14px;
        padding: 5px;
        border-radius: 7px;
        line-height: 1;
        transition: color .18s, background .18s;
    }

    .rg-eye:hover {
        color: #f15d30;
        background: rgba(241, 93, 48, .08);
    }

    .rg-err {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 500;
        margin-top: 5px;
    }

    /* Alert */
    .rg-alert {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 12px 15px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .rg-alert-err {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    /* Password strength */
    .pwd-str {
        margin-top: 8px;
    }

    .pwd-str-bar {
        display: flex;
        gap: 4px;
        margin-bottom: 4px;
    }

    .pwd-str-bar span {
        flex: 1;
        height: 4px;
        border-radius: 2px;
        background: #ebe8e5;
        transition: background .25s;
    }

    .pwd-str-lbl {
        font-size: 11.5px;
        font-weight: 600;
        color: #b0aaa4;
    }

    /* Terms */
    .rg-terms {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin: 16px 0 18px;
        padding: 14px 17px;
        background: #faf9f8;
        border: 1.5px solid #ebe8e5;
        border-radius: 12px;
        font-size: 13px;
        color: #78716c;
        transition: border-color .2s;
    }

    .rg-terms:focus-within {
        border-color: rgba(241, 93, 48, .3);
        background: #fffcfb;
    }

    .rg-terms input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin-top: 2px;
        accent-color: #f15d30;
        flex-shrink: 0;
        cursor: pointer;
    }

    .rg-terms label {
        cursor: pointer;
        line-height: 1.65;
    }

    .rg-terms a {
        color: #f15d30;
        font-weight: 600;
        text-decoration: none;
    }

    .rg-terms a:hover {
        text-decoration: underline;
    }

    /* Actions */
    .rg-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
        padding-top: 2px;
    }

    .rg-submit {
        height: 50px;
        min-width: 220px;
        padding: 0 34px;
        background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 15.5px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        letter-spacing: 0;
        white-space: nowrap;
        transition: transform .22s, box-shadow .22s;
        box-shadow: 0 4px 16px rgba(241, 93, 48, .28);
        position: relative;
        overflow: hidden;
    }

    .rg-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(241, 93, 48, .38);
    }

    .rg-submit:active {
        transform: translateY(0);
    }

    .rg-submit::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .15), transparent);
        transition: left .45s;
    }

    .rg-submit:hover::after {
        left: 100%;
    }

    .rg-switch {
        font-size: 14px;
        color: #b0aaa4;
        text-align: center;
    }

    .rg-switch a {
        color: #f15d30;
        font-weight: 700;
        text-decoration: none;
        border-bottom: 1.5px solid transparent;
        transition: border-color .2s;
    }

    .rg-switch a:hover {
        border-color: #f15d30;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .reg-banner {
            grid-template-columns: 1fr;
            justify-items: center;
            text-align: center;
            padding: 24px 28px;
        }

        .rb-tag {
            justify-self: center;
            text-align: center;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .reg-card {
            border-radius: 16px;
        }

        .reg-banner {
            padding: 22px 22px;
            flex-wrap: wrap;
        }

        .rb-steps,
        .rb-tag {
            display: none;
        }

        .reg-body {
            padding: 28px 20px 30px;
        }

        .rg-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .rg-submit {
            justify-content: center;
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
                    <span>Đăng ký <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Tạo tài khoản</h1>
            </div>
        </div>
    </div>
</section>

<div class="reg-wrap">
    <div class="container">
        <div class="reg-card">

            {{-- BANNER --}}
            <div class="reg-banner">
                <div class="rb-logo">
                    <div class="rb-logo-ic"><i class="fa fa-paper-plane"></i></div>
                    <div class="rb-logo-tx">
                        Miu Travel
                        <small>Du Lịch Việt Nam</small>
                    </div>
                </div>

                <div class="rb-steps">
                    <div class="rb-step on">
                        <div class="rb-step-n">1</div>
                        Thông tin
                    </div>
                    <div class="rb-line"></div>
                    <div class="rb-step">
                        <div class="rb-step-n">2</div>
                        Bảo mật
                    </div>
                    <div class="rb-line"></div>
                    <div class="rb-step">
                        <div class="rb-step-n">3</div>
                        Hoàn tất
                    </div>
                </div>

                <div class="rb-tag">
                    Tạo tài khoản miễn phí &amp; khám phá<br>hàng nghìn tour hấp dẫn.
                </div>
            </div>

            {{-- FORM BODY --}}
            <div class="reg-body">
                <div class="rg-head">
                    <h3>Tạo tài khoản mới ✨</h3>
                    <p>Điền thông tin bên dưới — chỉ mất khoảng 1 phút!</p>
                </div>

                @if ($errors->any())
                    <div class="rg-alert rg-alert-err">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('post.account.register') }}" method="POST" autocomplete="on" id="regForm">
                    @csrf

                    {{-- Thông tin cá nhân --}}
                    <div class="rg-sec">
                        <i class="fa fa-user"></i> Thông tin cá nhân
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-name">Họ và tên <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-user rg-ico"></i>
                                    <input type="text" id="rg-name" name="name" placeholder="Ví dụ: Nguyễn Văn A"
                                        value="{{ old('name') }}" autocomplete="name"
                                        class="{{ $errors->has('name') ? 'err' : '' }}">
                                </div>
                                @if ($errors->first('name'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i> {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-phone">Số điện thoại <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-phone rg-ico"></i>
                                    <input type="tel" id="rg-phone" name="phone" placeholder="Ví dụ: 0123 456 789"
                                        value="{{ old('phone') }}" autocomplete="tel"
                                        class="{{ $errors->has('phone') ? 'err' : '' }}">
                                </div>
                                @if ($errors->first('phone'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i> {{ $errors->first('phone') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-email">Email <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-envelope rg-ico"></i>
                                    <input type="email" id="rg-email" name="email"
                                        placeholder="Ví dụ: nguyenvana@gmail.com" value="{{ old('email') }}"
                                        autocomplete="email" class="{{ $errors->has('email') ? 'err' : '' }}">
                                </div>
                                @if ($errors->first('email'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i> {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-address">Địa chỉ <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-map-marker rg-ico"></i>
                                    <input type="text" id="rg-address" name="address"
                                        placeholder="Ví dụ: 566 Núi Thành, Hòa Cường, Đà Nẵng"
                                        value="{{ old('address') }}" autocomplete="street-address"
                                        class="{{ $errors->has('address') ? 'err' : '' }}">
                                </div>
                                @if ($errors->first('address'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i> {{ $errors->first('address') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Bảo mật --}}
                    <div class="rg-sec">
                        <i class="fa fa-lock"></i> Bảo mật tài khoản
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-pwd">Mật khẩu <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-lock rg-ico"></i>
                                    <input type="password" id="rg-pwd" name="password" placeholder="Tối thiểu 6 ký tự"
                                        autocomplete="new-password" oninput="checkStr(this.value)"
                                        class="{{ $errors->has('password') ? 'err' : '' }}">
                                    <button type="button" class="rg-eye" onclick="toggleP('rg-pwd',this)" tabindex="-1">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                <div class="pwd-str" id="pwdStr" style="display:none;">
                                    <div class="pwd-str-bar">
                                        <span id="ps1"></span><span id="ps2"></span>
                                        <span id="ps3"></span><span id="ps4"></span>
                                    </div>
                                    <div class="pwd-str-lbl" id="psLbl"></div>
                                </div>
                                @if ($errors->first('password'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i> {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rg-group">
                                <label for="rg-rpwd">Xác nhận mật khẩu <sup>*</sup></label>
                                <div class="rg-field">
                                    <i class="fa fa-lock rg-ico"></i>
                                    <input type="password" id="rg-rpwd" name="r_password"
                                        placeholder="Nhập lại mật khẩu" autocomplete="new-password"
                                        class="{{ $errors->has('r_password') ? 'err' : '' }}">
                                    <button type="button" class="rg-eye" onclick="toggleP('rg-rpwd',this)"
                                        tabindex="-1">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @if ($errors->first('r_password'))
                                    <div class="rg-err"><i class="fa fa-times-circle"></i>
                                        {{ $errors->first('r_password') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <div class="rg-terms">
                        <input type="checkbox" id="rg-terms" required>
                        <label for="rg-terms">
                            Tôi đã đọc và đồng ý với
                            <a href="#">Điều khoản dịch vụ</a> và
                            <a href="#">Chính sách bảo mật</a> của Miu Travel
                        </label>
                    </div>

                    <div class="rg-actions">
                        <button type="submit" class="rg-submit" id="rgBtn">
                            <i class="fa fa-user-plus"></i> Tạo tài khoản
                        </button>
                        <div class="rg-switch">
                            Đã có tài khoản?
                            <a href="{{ route('page.user.account') }}">Đăng nhập ngay →</a>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@stop

@section('script')
<script>
    function toggleP(id, btn) {
        const el = document.getElementById(id);
        const ic = btn.querySelector('i');
        el.type = el.type === 'password' ? 'text' : 'password';
        ic.classList.toggle('fa-eye');
        ic.classList.toggle('fa-eye-slash');
    }

    function checkStr(v) {
        const el = document.getElementById('pwdStr');
        const ss = ['ps1', 'ps2', 'ps3', 'ps4'].map(id => document.getElementById(id));
        const lb = document.getElementById('psLbl');
        if (!v) { el.style.display = 'none'; return; }
        el.style.display = 'block';
        let sc = 0;
        if (v.length >= 6) sc++;
        if (v.length >= 10) sc++;
        if (/[A-Z]/.test(v) && /[0-9]/.test(v)) sc++;
        if (/[^A-Za-z0-9]/.test(v)) sc++;
        const cl = ['#f87171', '#fb923c', '#facc15', '#4ade80'];
        const tx = ['Rất yếu', 'Yếu', 'Trung bình', 'Mạnh 💪'];
        ss.forEach((s, i) => s.style.background = i < sc ? cl[sc - 1] : '#ebe8e5');
        lb.textContent = tx[sc - 1] || '';
        lb.style.color = cl[sc - 1] || '#b0aaa4';
    }

    /* Ripple */
    const rgBtn = document.getElementById('rgBtn');
    if (rgBtn) {
        rgBtn.addEventListener('click', function (e) {
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
