@extends('page.layouts.page')
@section('title', 'Đăng nhập | Miu Travel')
@section('style')
<style>
/* ═══════════════════════════════════════════════
   ĐĂNG NHẬP — LIGHT & ELEGANT
═══════════════════════════════════════════════ */
.auth-wrap {
    min-height: calc(100vh - 260px);
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 64px 0;
    font-family: 'Inter', sans-serif;
}

.auth-wrap > .container {
    display: flex;
    justify-content: center;
}

/* Card */
.auth-card {
    width: 100%;
    max-width: 1040px;
    margin: 0 auto;
    display: flex;
    border-radius: 20px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,.04),
                0 8px 24px rgba(0,0,0,.07),
                0 32px 64px rgba(0,0,0,.05);
    animation: fadeUp .45s ease both;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ─────────────── LEFT PANEL ─────────────── */
.auth-panel {
    width: 360px;
    flex-shrink: 0;
    background: linear-gradient(160deg, #f15d30 0%, #e8472a 60%, #c93c1e 100%);
    padding: 52px 36px;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}
/* Soft circle decoration */
.auth-panel::before {
    content: '';
    position: absolute;
    width: 260px; height: 260px;
    top: -80px; right: -80px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    pointer-events: none;
}
.auth-panel::after {
    content: '';
    position: absolute;
    width: 180px; height: 180px;
    bottom: -55px; left: -55px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    pointer-events: none;
}

/* Logo */
.ap-logo {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 56px;
    position: relative;
    z-index: 1;
}
.ap-logo-mark {
    width: 42px; height: 42px;
    background: rgba(255,255,255,.22);
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
}
.ap-logo-mark i { color: #fff; font-size: 17px; }
.ap-logo-text {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem; font-weight: 800;
    color: #fff; line-height: 1.1;
}
.ap-logo-text span {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 8.5px; font-weight: 700;
    letter-spacing: 3px; text-transform: uppercase;
    color: rgba(255,255,255,.7); margin-top: 3px;
}

/* Heading */
.ap-body { flex: 1; position: relative; z-index: 1; }
.ap-body h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem; font-weight: 800;
    color: #fff; line-height: 1.3; margin-bottom: 10px;
}
.ap-body p {
    font-size: 13.5px; color: rgba(255,255,255,.72);
    line-height: 1.8; margin-bottom: 36px;
}

/* Features */
.ap-features { display: flex; flex-direction: column; gap: 11px; }
.ap-feat {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px;
    background: rgba(255,255,255,.13);
    border-radius: 11px;
    transition: background .2s, transform .2s;
    cursor: default;
}
.ap-feat:hover { background: rgba(255,255,255,.2); transform: translateX(3px); }
.ap-feat-ic {
    width: 30px; height: 30px;
    background: rgba(255,255,255,.18);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: #fff; flex-shrink: 0;
}
.ap-feat-tx { font-size: 13px; color: rgba(255,255,255,.88); font-weight: 500; }

/* Quote footer */
.ap-footer {
    position: relative; z-index: 1;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,.18);
    font-size: 12px; color: rgba(255,255,255,.58);
    font-style: italic; line-height: 1.7;
}

/* ─────────────── RIGHT FORM ─────────────── */
.auth-form {
    flex: 1;
    padding: 52px 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: #fff;
}

.af-head { margin-bottom: 30px; }
.af-head h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem; font-weight: 800;
    color: #1a1a1a; margin-bottom: 6px;
}
.af-head p { font-size: 14px; color: #b0aaa4; margin: 0; }

/* Input group */
.af-group { margin-bottom: 18px; }
.af-group label {
    display: block;
    font-size: 13px; font-weight: 600;
    color: #3d3a38; margin-bottom: 7px;
}
.af-group label sup { color: #f15d30; }
.af-field { position: relative; }
.af-field .af-ico {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    font-size: 14px; color: #ccc9c6;
    pointer-events: none; transition: color .2s;
}
.af-field:focus-within .af-ico { color: #f15d30; }
.af-field input {
    width: 100%; height: 50px;
    border: 1.5px solid #ebe8e5;
    border-radius: 11px;
    padding: 0 46px 0 42px;
    font-size: 14.5px; font-family: 'Inter', sans-serif;
    color: #1a1a1a; background: #faf9f8;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.af-field input::placeholder { color: #c4c0bb; font-size: 13.5px; }
.af-field input:focus {
    border-color: #f15d30;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(241,93,48,.1);
}
.af-field input.err { border-color: #fca5a5; background: #fff8f7; }
.af-eye {
    position: absolute; right: 12px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; color: #c4c0bb;
    cursor: pointer; font-size: 14px; padding: 5px;
    border-radius: 7px; line-height: 1;
    transition: color .18s, background .18s;
}
.af-eye:hover { color: #f15d30; background: rgba(241,93,48,.08); }
.af-forgot {
    display: flex;
    justify-content: flex-end;
    margin-top: -6px;
    margin-bottom: 16px;
}
.af-forgot a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #f15d30;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}
.af-forgot a:hover { color: #d9461f; text-decoration: none; }
.af-err {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; color: #ef4444; font-weight: 500;
    margin-top: 5px;
}

/* Alert */
.af-alert {
    display: flex; align-items: center; gap: 9px;
    padding: 12px 15px; border-radius: 10px;
    font-size: 13.5px; font-weight: 500;
    margin-bottom: 22px;
}
.af-alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
.af-alert-ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

/* Submit */
.af-submit {
    width: 100%; height: 52px;
    background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
    color: #fff; border: none; border-radius: 12px;
    font-size: 15.5px; font-weight: 700;
    font-family: 'Inter', sans-serif; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 9px;
    letter-spacing: .3px; margin-top: 6px;
    transition: transform .22s, box-shadow .22s;
    box-shadow: 0 4px 16px rgba(241,93,48,.28);
    position: relative; overflow: hidden;
}
.af-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(241,93,48,.38);
}
.af-submit:active { transform: translateY(0); }
.af-submit::after {
    content: '';
    position: absolute; top:0; left:-100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
    transition: left .45s;
}
.af-submit:hover::after { left: 100%; }

/* Divider */
.af-or {
    display: flex; align-items: center; gap: 12px;
    margin: 22px 0 18px;
    font-size: 11.5px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 1.5px; color: #d6d0cb;
}
.af-or::before, .af-or::after { content: ''; flex: 1; height: 1px; background: #ebe8e5; }

/* Socials */
.af-socials { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.af-soc {
    height: 46px;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    border: 1.5px solid #ebe8e5; border-radius: 11px;
    background: #faf9f8; font-size: 13.5px; font-weight: 600;
    font-family: 'Inter', sans-serif; color: #3d3a38;
    text-decoration: none; cursor: pointer;
    transition: all .2s;
}
.af-soc:hover {
    background: #fff; border-color: #d6d0cb;
    box-shadow: 0 2px 10px rgba(0,0,0,.06);
    transform: translateY(-1px); color: #1a1a1a;
    text-decoration: none;
}

/* Switch link */
.af-switch {
    text-align: center; margin-top: 22px;
    font-size: 14px; color: #b0aaa4;
}
.af-switch a {
    color: #f15d30; font-weight: 700;
    text-decoration: none;
    border-bottom: 1.5px solid transparent;
    transition: border-color .2s;
}
.af-switch a:hover { border-color: #f15d30; }

/* Responsive */
@media (max-width: 820px) {
    .auth-card { flex-direction: column; border-radius: 16px; }
    .auth-panel { width: auto; padding: 32px 28px; }
    .auth-panel::before, .auth-panel::after { display: none; }
    .ap-features, .ap-footer, .ap-body p { display: none; }
    .ap-body h2 { font-size: 1.35rem; margin: 0; }
    .ap-logo { margin-bottom: 16px; }
    .auth-form { padding: 32px 24px; }
    .af-socials { grid-template-columns: 1fr; }
}
</style>
@stop

@section('content')
<section class="hero-wrap hero-wrap-2 account-page-hero" style="background: #ffffff; background-image: none;">
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start" style="min-height:200px;padding-bottom:30px;">
            <div class="col-md-9 ftco-animate pb-5 text-left">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                    <span>Đăng nhập <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Đăng nhập</h1>
            </div>
        </div>
    </div>
</section>

<div class="auth-wrap">
    <div class="container">
        <div class="auth-card">

            {{-- LEFT PANEL --}}
            <div class="auth-panel">
                <div class="ap-logo">
                    <div class="ap-logo-mark"><i class="fa fa-paper-plane"></i></div>
                    <div class="ap-logo-text">
                        Miu Travel
                        <span>Du Lịch Việt Nam</span>
                    </div>
                </div>

                <div class="ap-body">
                    <h2>Chào mừng<br>trở lại! 🌏</h2>
                    <p>Đăng nhập để quản lý tour, theo dõi lịch trình và khám phá ưu đãi dành riêng cho thành viên.</p>

                    <div class="ap-features">
                        <div class="ap-feat">
                            <div class="ap-feat-ic"><i class="fa fa-map-signs"></i></div>
                            <div class="ap-feat-tx">Quản lý tour & lịch trình</div>
                        </div>
                        <div class="ap-feat">
                            <div class="ap-feat-ic"><i class="fa fa-tag"></i></div>
                            <div class="ap-feat-tx">Ưu đãi độc quyền thành viên</div>
                        </div>
                        <div class="ap-feat">
                            <div class="ap-feat-ic"><i class="fa fa-headphones"></i></div>
                            <div class="ap-feat-tx">Hỗ trợ 24/7 tận tâm</div>
                        </div>
                    </div>

                    <div class="ap-footer">
                        "Mỗi chuyến đi là một cuốn sách — hãy để Miu Travel giúp bạn viết những trang đẹp nhất."
                    </div>
                </div>
            </div>

            {{-- RIGHT FORM --}}
            <div class="auth-form">
                <div class="af-head">
                    <h3>Đăng nhập</h3>
                    <p>Nhập email và mật khẩu để tiếp tục hành trình ✈️</p>
                </div>

                @if ($errors->any())
                    <div class="af-alert af-alert-err">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="af-alert af-alert-ok">
                        <i class="fa fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('account.login') }}" method="POST" autocomplete="on">
                    @csrf

                    <div class="af-group">
                        <label for="lg-email">Địa chỉ Email <sup>*</sup></label>
                        <div class="af-field">
                            <i class="fa fa-envelope af-ico"></i>
                            <input type="email" id="lg-email" name="email"
                                placeholder="vd: nguyenvana@gmail.com"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                class="{{ $errors->has('email') ? 'err' : '' }}">
                        </div>
                        @if ($errors->first('email'))
                            <div class="af-err"><i class="fa fa-times-circle"></i> {{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="af-group">
                        <label for="lg-password">Mật khẩu <sup>*</sup></label>
                        <div class="af-field">
                            <i class="fa fa-lock af-ico"></i>
                            <input type="password" id="lg-password" name="password"
                                placeholder="Nhập mật khẩu của bạn"
                                autocomplete="current-password"
                                class="{{ $errors->has('password') ? 'err' : '' }}">
                            <button type="button" class="af-eye" onclick="toggleP('lg-password',this)" tabindex="-1">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        @if ($errors->first('password'))
                            <div class="af-err"><i class="fa fa-times-circle"></i> {{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="af-forgot">
                        <a href="{{ route('page.user.forgot.password') }}">
                            <i class="fa fa-key"></i> Quên mật khẩu?
                        </a>
                    </div>

                    <button type="submit" class="af-submit" id="lgBtn">
                        <i class="fa fa-sign-in"></i> Đăng nhập
                    </button>
                </form>

                <div class="af-or">hoặc tiếp tục với</div>

                <div class="af-socials">
                    <a href="#" class="af-soc">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        Google
                    </a>
                    <a href="#" class="af-soc">
                        <svg width="16" height="16" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </a>
                </div>

                <div class="af-switch">
                    Chưa có tài khoản? <a href="{{ route('user.register') }}">Đăng ký miễn phí →</a>
                </div>
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

/* Shimmer ripple on submit */
document.getElementById('lgBtn').addEventListener('click', function(e) {
    const r = document.createElement('span');
    const rc = this.getBoundingClientRect();
    const sz = Math.max(this.clientWidth, this.clientHeight);
    r.style.cssText = `position:absolute;width:${sz}px;height:${sz}px;top:${e.clientY-rc.top-sz/2}px;left:${e.clientX-rc.left-sz/2}px;background:rgba(255,255,255,.22);border-radius:50%;transform:scale(0);animation:rA .5s ease-out;pointer-events:none;`;
    this.appendChild(r);
    if (!window.__rs) { window.__rs=1; const s=document.createElement('style'); s.textContent='@keyframes rA{to{transform:scale(2.5);opacity:0;}}'; document.head.appendChild(s); }
    setTimeout(() => r.remove(), 550);
});
</script>
@stop
