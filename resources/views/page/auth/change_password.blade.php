@extends('page.layouts.page')
@section('title', 'Đổi mật khẩu | Miu Travel')
@section('style')
<style>
/* Reuse account-wrap & shared styles */
.account-wrap {
    min-height: calc(100vh - 260px);
    background: #f7f3ef;
    padding: 56px 0 72px;
    font-family: 'Inter', sans-serif;
}
.account-layout { display: flex; gap: 28px; align-items: flex-start; }

/* Sidebar */
.user-sidebar {
    width: 260px; flex-shrink: 0; background: #fff;
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07);
    position: sticky; top: 24px;
}
.us-profile {
    background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
    padding: 28px 20px 24px; text-align: center; position: relative; overflow: hidden;
}
.us-profile::before {
    content: ''; position: absolute; width: 180px; height: 180px;
    top: -70px; right: -50px; border-radius: 50%;
    background: rgba(255,255,255,.1); pointer-events: none;
}
.us-avatar { width: 78px; height: 78px; border-radius: 50%; border: 3px solid rgba(255,255,255,.5); overflow: hidden; margin: 0 auto 12px; background: #fff; }
.us-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
.us-name { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 3px; position: relative; z-index: 1; }
.us-email { font-size: 12px; color: rgba(255,255,255,.72); position: relative; z-index: 1; word-break: break-all; }
.us-nav { padding: 10px 0; }
.us-nav-item {
    display: flex; align-items: center; gap: 11px; padding: 13px 20px;
    font-size: 14px; font-weight: 500; color: #57534e;
    text-decoration: none; transition: background .18s, color .18s;
    border-left: 3px solid transparent;
}
.us-nav-item:hover { background: #fef6f0; color: #f15d30; border-left-color: #f15d30; text-decoration: none; }
.us-nav-item.active { background: #fef3ee; color: #f15d30; font-weight: 600; border-left-color: #f15d30; text-decoration: none; }
.us-nav-ic { width: 28px; height: 28px; background: #f5f0ec; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #a8a29e; flex-shrink: 0; transition: background .18s, color .18s; }
.us-nav-item:hover .us-nav-ic, .us-nav-item.active .us-nav-ic { background: rgba(241,93,48,.12); color: #f15d30; }
.us-nav-logout { border-top: 1px solid #f5f0ec; margin-top: 6px; color: #ef4444; }
.us-nav-logout:hover { background: #fff5f5; color: #ef4444; border-left-color: #ef4444; }
.us-nav-logout .us-nav-ic { background: #fee2e2; color: #ef4444; }

/* Card */
.account-card { flex: 1; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07); }
.ac-head { padding: 24px 36px; border-bottom: 1px solid #f5f0ec; display: flex; align-items: center; gap: 12px; }
.ac-head-ic { width: 40px; height: 40px; background: rgba(241,93,48,.1); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #f15d30; font-size: 16px; }
.ac-head h2 { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 800; color: #1a1a1a; margin: 0; }
.ac-head p { font-size: 13px; color: #a8a29e; margin: 0; }
.ac-body { padding: 36px; }

/* Fields */
.ac-sec { display: flex; align-items: center; gap: 9px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #b0aaa4; margin-bottom: 20px; }
.ac-sec i { color: #f15d30; }
.ac-sec::after { content: ''; flex: 1; height: 1px; background: #ebe8e5; }
.ac-group { margin-bottom: 20px; }
.ac-group label { display: block; font-size: 13px; font-weight: 600; color: #3d3a38; margin-bottom: 8px; }
.ac-group label sup { color: #f15d30; }
.ac-field { position: relative; }
.ac-field .ac-ico { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #ccc9c6; pointer-events: none; transition: color .2s; }
.ac-field:focus-within .ac-ico { color: #f15d30; }
.ac-field input {
    width: 100%; height: 50px; border: 1.5px solid #ebe8e5; border-radius: 11px;
    padding: 0 48px 0 42px; font-size: 14.5px; font-family: 'Inter', sans-serif;
    color: #1a1a1a; background: #faf9f8; outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.ac-field input::placeholder { color: #c4c0bb; font-size: 13.5px; }
.ac-field input:focus { border-color: #f15d30; background: #fff; box-shadow: 0 0 0 3px rgba(241,93,48,.1); }
.ac-field input.err { border-color: #fca5a5; background: #fff8f7; }
.ac-eye { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #c4c0bb; cursor: pointer; font-size: 14px; padding: 5px; border-radius: 7px; line-height: 1; transition: color .18s, background .18s; }
.ac-eye:hover { color: #f15d30; background: rgba(241,93,48,.08); }
.ac-err { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #ef4444; font-weight: 500; margin-top: 5px; }

/* Hint card */
.pwd-hint {
    background: #fef9f6;
    border: 1px solid rgba(241,93,48,.15);
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 24px;
}
.pwd-hint h5 { font-size: 13px; font-weight: 700; color: #3d3a38; margin-bottom: 8px; }
.pwd-hint ul { margin: 0; padding-left: 16px; }
.pwd-hint li { font-size: 13px; color: #78716c; margin-bottom: 4px; line-height: 1.6; }

/* Strength */
.pwd-str { margin-top: 8px; }
.pwd-str-bar { display: flex; gap: 4px; margin-bottom: 4px; }
.pwd-str-bar span { flex: 1; height: 4px; border-radius: 2px; background: #ebe8e5; transition: background .25s; }
.pwd-str-lbl { font-size: 11.5px; font-weight: 600; color: #b0aaa4; }

/* Alert */
.ac-alert { display: flex; align-items: center; gap: 9px; padding: 12px 16px; border-radius: 11px; font-size: 13.5px; font-weight: 500; margin-bottom: 24px; }
.ac-alert-err { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
.ac-alert-ok  { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }

/* Actions */
.ac-actions { display: flex; align-items: center; gap: 14px; padding-top: 8px; margin-top: 8px; border-top: 1px solid #f5f0ec; }
.ac-save {
    height: 50px; padding: 0 36px;
    background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
    color: #fff; border: none; border-radius: 12px;
    font-size: 15px; font-weight: 700; font-family: 'Inter', sans-serif; cursor: pointer;
    display: inline-flex; align-items: center; gap: 9px;
    transition: transform .22s, box-shadow .22s;
    box-shadow: 0 4px 16px rgba(241,93,48,.28);
    position: relative; overflow: hidden;
}
.ac-save:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(241,93,48,.38); }
.ac-save:active { transform: translateY(0); }
.ac-save::after { content: ''; position: absolute; top:0; left:-100%; width:100%; height:100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent); transition: left .45s; }
.ac-save:hover::after { left: 100%; }

.ac-cancel { height: 50px; padding: 0 24px; background: #fff; border: 1.5px solid #ebe8e5; border-radius: 12px; font-size: 14.5px; font-weight: 600; font-family: 'Inter', sans-serif; color: #78716c; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; text-decoration: none; }
.ac-cancel:hover { border-color: #d6d0cb; background: #faf9f8; color: #57534e; text-decoration: none; }

@media (max-width: 900px) {
    .account-layout { flex-direction: column; gap: 20px; }
    .user-sidebar { width: 100%; position: static; }
    .us-nav { display: flex; flex-wrap: wrap; padding: 8px; gap: 4px; }
    .us-nav-item { border-left: none; border-radius: 8px; padding: 10px 14px; flex: 1; justify-content: center; min-width: 120px; }
    .us-nav-item.active, .us-nav-item:hover { border-left-color: transparent; }
    .ac-body { padding: 24px 20px; }
    .ac-head { padding: 20px 24px; }
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
                    <span>Đổi mật khẩu <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Đổi mật khẩu</h1>
            </div>
        </div>
    </div>
</section>

<div class="account-wrap">
    <div class="container">
        <div class="account-layout">

            @include('page.common.sideBarUser')

            <div class="account-card" style="flex:1;">

                <div class="ac-head">
                    <div class="ac-head-ic"><i class="fa fa-lock"></i></div>
                    <div>
                        <h2>Đổi mật khẩu</h2>
                        <p>Nhập mật khẩu hiện tại và mật khẩu mới để cập nhật</p>
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

                    <div class="pwd-hint">
                        <h5><i class="fa fa-info-circle" style="color:#f15d30;margin-right:6px;"></i>Yêu cầu mật khẩu</h5>
                        <ul>
                            <li>Tối thiểu <strong>6 ký tự</strong></li>
                            <li>Nên kết hợp chữ hoa, chữ thường và số</li>
                            <li>Không sử dụng mật khẩu giống tài khoản khác</li>
                        </ul>
                    </div>

                    <form action="{{ route('post.change.password') }}" method="POST">
                        @csrf

                        <div class="ac-sec"><i class="fa fa-lock"></i> Thay đổi mật khẩu</div>

                        <div class="ac-group">
                            <label for="cp-current">Mật khẩu hiện tại <sup>*</sup></label>
                            <div class="ac-field">
                                <i class="fa fa-lock ac-ico"></i>
                                <input type="password" id="cp-current" name="c_password"
                                    placeholder="Nhập mật khẩu đang dùng"
                                    class="{{ $errors->has('c_password') ? 'err' : '' }}">
                                <button type="button" class="ac-eye" onclick="toggleP('cp-current',this)" tabindex="-1">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            @if ($errors->first('c_password'))
                                <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('c_password') }}</div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="cp-new">Mật khẩu mới <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-key ac-ico"></i>
                                        <input type="password" id="cp-new" name="password"
                                            placeholder="Tối thiểu 6 ký tự"
                                            oninput="checkStr(this.value)"
                                            class="{{ $errors->has('password') ? 'err' : '' }}">
                                        <button type="button" class="ac-eye" onclick="toggleP('cp-new',this)" tabindex="-1">
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
                                        <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ac-group">
                                    <label for="cp-confirm">Xác nhận mật khẩu mới <sup>*</sup></label>
                                    <div class="ac-field">
                                        <i class="fa fa-key ac-ico"></i>
                                        <input type="password" id="cp-confirm" name="r_password"
                                            placeholder="Nhập lại mật khẩu mới"
                                            class="{{ $errors->has('r_password') ? 'err' : '' }}">
                                        <button type="button" class="ac-eye" onclick="toggleP('cp-confirm',this)" tabindex="-1">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    @if ($errors->first('r_password'))
                                        <div class="ac-err"><i class="fa fa-times-circle"></i> {{ $errors->first('r_password') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="ac-actions">
                            <button type="submit" class="ac-save" id="cpSaveBtn">
                                <i class="fa fa-check"></i> Cập nhật mật khẩu
                            </button>
                            <a href="{{ route('info.account') }}" class="ac-cancel">
                                <i class="fa fa-arrow-left"></i> Quay lại
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
function toggleP(id, btn) {
    const el = document.getElementById(id);
    const ic = btn.querySelector('i');
    el.type = el.type === 'password' ? 'text' : 'password';
    ic.classList.toggle('fa-eye');
    ic.classList.toggle('fa-eye-slash');
}

function checkStr(v) {
    const el = document.getElementById('pwdStr');
    const ss = ['ps1','ps2','ps3','ps4'].map(id => document.getElementById(id));
    const lb = document.getElementById('psLbl');
    if (!v) { el.style.display = 'none'; return; }
    el.style.display = 'block';
    let sc = 0;
    if (v.length >= 6)  sc++;
    if (v.length >= 10) sc++;
    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) sc++;
    if (/[^A-Za-z0-9]/.test(v)) sc++;
    const cl = ['#f87171','#fb923c','#facc15','#4ade80'];
    const tx = ['Rất yếu','Yếu','Trung bình','Mạnh 💪'];
    ss.forEach((s, i) => s.style.background = i < sc ? cl[sc-1] : '#ebe8e5');
    lb.textContent = tx[sc-1] || '';
    lb.style.color = cl[sc-1] || '#b0aaa4';
}
</script>
@stop
