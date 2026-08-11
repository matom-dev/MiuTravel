@extends('page.layouts.page')
@section('title', 'Liên hệ | Miu Travel - Du lịch Việt')
@section('style')
<style>
/* ════════════════════════════════════════════
   CONTACT PAGE — Miu Travel
════════════════════════════════════════════ */

/* ── Body wrapper ── */
.contact-page { background: #f8f9fc; }
.contact-page .container,
.hero-wrap.hero-wrap-2 > .container {
    width: min(100% - 28px, 1320px);
    max-width: 1320px;
}

/* ════════════════════
   INFO CARDS ROW
════════════════════ */
.contact-info-section {
    padding: 68px 0 0;
    background: #f8f9fc;
}
.contact-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 0;
}
@media (max-width: 991px) { .contact-info-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px)  { .contact-info-grid { grid-template-columns: 1fr; } }

.contact-info-card {
    background: #fff;
    border-radius: 20px;
    padding: 34px 28px;
    text-align: center;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
    border: 1px solid rgba(0,0,0,.04);
    transition: transform .3s ease, box-shadow .3s ease;
}
.contact-info-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,.1);
}
.contact-info-icon {
    width: 60px; height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary,#f15d30), #ff8c5a);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 6px 20px rgba(241,93,48,.3);
}
.contact-info-icon .fa {
    font-size: 24px; color: #fff;
}
.contact-info-card h4 {
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 8px;
}
.contact-info-card p,
.contact-info-card a {
    font-size: 17px;
    font-weight: 600;
    color: #1a202c;
    text-decoration: none;
    margin: 0;
    transition: color .2s;
    line-height: 1.5;
}
.contact-info-card a:hover { color: var(--primary,#f15d30); }

/* ════════════════════
   MAIN SECTION: Form + Map
════════════════════ */
.contact-main-section {
    padding: 58px 0 84px;
    background: #f8f9fc;
}
.contact-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(420px, .85fr);
    gap: 42px;
    align-items: start;
}
@media (max-width: 991px) { .contact-layout { grid-template-columns: 1fr; } }
@media (max-width: 575px) {
    .contact-page .container,
    .hero-wrap.hero-wrap-2 > .container { width: min(100% - 24px, 1320px); }
    .contact-form-card { padding: 26px 22px; }
    .contact-info-card { padding: 26px 22px; }
    .cf-input-wrap input,
    .cf-input-wrap select,
    .cf-input-wrap textarea { font-size: 16px; }
}

/* ── Form card ── */
.contact-form-card {
    background: #fff;
    border-radius: 24px;
    padding: 48px 46px;
    box-shadow: 0 8px 40px rgba(0,0,0,.08);
    border: 1px solid rgba(0,0,0,.04);
}
.contact-form-header {
    margin-bottom: 28px;
}
.contact-form-header .sub {
    display: inline-block;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: var(--primary,#f15d30);
    margin-bottom: 8px;
}
.contact-form-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.15rem, 2.8vw, 2.8rem);
    font-weight: 800;
    color: #1a202c;
    margin: 0 0 10px;
    line-height: 1.3;
}
.contact-form-header p {
    font-size: 17px;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
}

/* ── Form fields ── */
.cf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
@media (max-width: 575px) { .cf-row { grid-template-columns: 1fr; } }

.cf-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 22px;
}
.cf-group label {
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #475569;
    margin-bottom: 9px;
}
.cf-input-wrap { position: relative; }
.cf-input-wrap .cf-icon {
    position: absolute;
    left: 17px; top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 17px;
    pointer-events: none;
}
.cf-input-wrap textarea ~ .cf-icon,
.cf-group--textarea .cf-icon {
    top: 16px; transform: none;
}
.cf-input-wrap input,
.cf-input-wrap select,
.cf-input-wrap textarea {
    width: 100%;
    min-height: 58px;
    padding: 15px 17px 15px 50px;
    border: 2px solid #e8edf2;
    border-radius: 12px;
    font-size: 18px;
    color: #1a202c;
    background: #f8fafc;
    outline: none;
    transition: border-color .25s, box-shadow .25s, background .25s;
    font-family: inherit;
    appearance: none;
}
.cf-input-wrap input:focus,
.cf-input-wrap select:focus,
.cf-input-wrap textarea:focus {
    border-color: var(--primary,#f15d30);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(241,93,48,.08);
}
.cf-input-wrap input::placeholder,
.cf-input-wrap textarea::placeholder { color: #b0bec5; }
.cf-input-wrap textarea {
    resize: vertical;
    min-height: 156px;
    padding-top: 14px;
    line-height: 1.6;
}
.cf-input-wrap select {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394a3b8' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}

/* ── Submit btn ── */
.cf-submit {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-height: 62px;
    padding: 18px 22px;
    background: linear-gradient(135deg, var(--primary,#f15d30), var(--primary-dark,#d94a1e));
    color: #fff;
    border: none;
    border-radius: 14px;
    font-size: 19px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 6px 24px rgba(241,93,48,.35);
    transition: transform .2s, box-shadow .2s;
    font-family: inherit;
    margin-top: 6px;
}
.cf-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(241,93,48,.45);
}
.cf-submit .fa { font-size: 18px; }

/* ── Guarantee strip ── */
.cf-guarantee {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 18px;
    flex-wrap: wrap;
}
.cf-guarantee-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}
.cf-guarantee-item .fa { color: #22c55e; font-size: 13px; }

/* ── Map card ── */
.contact-map-col {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.contact-map-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,.08);
    border: 1px solid rgba(0,0,0,.04);
}
.contact-map-card iframe {
    display: block;
    width: 100%;
    height: 390px;
    border: none;
}

/* ── Socials card ── */
.contact-social-card {
    background: linear-gradient(135deg, #0f1824, #1a2a42);
    border-radius: 20px;
    padding: 28px 28px;
    box-shadow: 0 8px 32px rgba(0,0,0,.15);
}
.contact-social-card h4 {
    font-size: 19px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 16px;
}
.contact-social-links {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.contact-social-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 50px;
    font-size: 15.5px;
    font-weight: 600;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    border: 1.5px solid rgba(255,255,255,.15);
    color: #fff;
}
.contact-social-link:hover {
    transform: translateY(-2px);
    text-decoration: none;
    color: #fff;
}
.contact-social-link--fb   { background: rgba(24,119,242,.25); border-color: rgba(24,119,242,.4); }
.contact-social-link--fb:hover { background: #1877f2; box-shadow: 0 6px 20px rgba(24,119,242,.4); }
.contact-social-link--zl   { background: rgba(0,104,255,.25); border-color: rgba(0,104,255,.4); }
.contact-social-link--zl:hover { background: #0068ff; box-shadow: 0 6px 20px rgba(0,104,255,.4); }
.contact-social-link--yt   { background: rgba(255,0,0,.2); border-color: rgba(255,0,0,.3); }
.contact-social-link--yt:hover { background: #ff0000; box-shadow: 0 6px 20px rgba(255,0,0,.35); }

/* ── Alert ── */
.cf-alert {
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cf-alert--success {
    background: rgba(34,197,94,.08);
    border: 1.5px solid rgba(34,197,94,.3);
    color: #16a34a;
}
.cf-alert--error {
    background: rgba(239,68,68,.08);
    border: 1.5px solid rgba(239,68,68,.3);
    color: #dc2626;
}
</style>
@stop
@section('seo')
<meta name="description" content="Liên hệ với Miu Travel để được tư vấn tour du lịch tốt nhất. Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7.">
@stop

@section('content')

{{-- ── Hero ── --}}
<section class="hero-wrap hero-wrap-2 js-fullheight"
    style="background: #ffffff; background-image: none;">
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
            <div class="col-md-9 ftco-animate pb-5 text-left">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                    <span>Liên hệ <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Liên hệ với chúng tôi</h1>
            </div>
        </div>
    </div>
</section>

<div class="contact-page">

    {{-- ── Info cards ── --}}
    <section class="contact-info-section">
        <div class="container">
            <div class="contact-info-grid">
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fa fa-map-marker"></i></div>
                    <h4>Địa chỉ</h4>
                    <p>Phường An Hải, Tp. Đà Nẵng</p>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fa fa-phone"></i></div>
                    <h4>Hotline</h4>
                    <a href="tel:0886733538">0886 733 538</a>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fa fa-envelope"></i></div>
                    <h4>Email</h4>
                    <a href="mailto:letoantrung73@gmail.com">letoantrung73@gmail.com</a>
                </div>
                <div class="contact-info-card">
                    <div class="contact-info-icon"><i class="fa fa-clock-o"></i></div>
                    <h4>Giờ làm việc</h4>
                    <p>T2 – T7: 8:00 – 17:30<br><small style="color:#94a3b8;font-weight:400;">CN: Nghỉ</small></p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Form + Map ── --}}
    <section class="contact-main-section">
        <div class="container">
            <div class="contact-layout">

                {{-- Form gửi tin nhắn --}}
                <div class="contact-form-card">
                    <div class="contact-form-header">
                        <span class="sub"><i class="fa fa-paper-plane" style="margin-right:5px;"></i> Gửi tin nhắn</span>
                        <h2>Chúng tôi luôn lắng nghe bạn</h2>
                        <p>Hãy để lại thông tin, đội ngũ tư vấn của Miu Travel sẽ liên hệ lại trong vòng 24 giờ.</p>
                    </div>

                    @if(session('success'))
                        <div class="cf-alert cf-alert--success">
                            <i class="fa fa-check-circle fa-lg"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="cf-alert cf-alert--error">
                            <i class="fa fa-times-circle fa-lg"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" id="contact-form">
                        @csrf

                        <div class="cf-row">
                            <div class="cf-group">
                                <label>Họ và tên <span style="color:#ef4444;">*</span></label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-user cf-icon"></i>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        placeholder="Nguyễn Văn A" required>
                                </div>
                                @error('name')<span style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</span>@enderror
                            </div>
                            <div class="cf-group">
                                <label>Số điện thoại <span style="color:#ef4444;">*</span></label>
                                <div class="cf-input-wrap">
                                    <i class="fa fa-phone cf-icon"></i>
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                        placeholder="0886 733 538" required>
                                </div>
                                @error('phone')<span style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="cf-group">
                            <label>Email</label>
                            <div class="cf-input-wrap">
                                <i class="fa fa-envelope cf-icon"></i>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="example@email.com">
                            </div>
                        </div>

                        <div class="cf-group">
                            <label>Dịch vụ quan tâm</label>
                            <div class="cf-input-wrap">
                                <i class="fa fa-compass cf-icon"></i>
                                <select name="service">
                                    <option value="">-- Chọn dịch vụ --</option>
                                    <option value="tour_domestic" {{ old('service') == 'tour_domestic' ? 'selected' : '' }}>Tour du lịch trong nước</option>
                                    <option value="tour_international" {{ old('service') == 'tour_international' ? 'selected' : '' }}>Tour du lịch nước ngoài</option>
                                    <option value="hotel" {{ old('service') == 'hotel' ? 'selected' : '' }}>Tư vấn nơi lưu trú</option>
                                    <option value="custom" {{ old('service') == 'custom' ? 'selected' : '' }}>Tour theo yêu cầu</option>
                                    <option value="other" {{ old('service') == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="cf-group cf-group--textarea">
                            <label>Nội dung <span style="color:#ef4444;">*</span></label>
                            <div class="cf-input-wrap">
                                <i class="fa fa-comment cf-icon"></i>
                                <textarea name="message" placeholder="Bạn cần tư vấn về tour nào? Số lượng người, thời gian, ngân sách dự kiến..." required>{{ old('message') }}</textarea>
                            </div>
                            @error('message')<span style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="cf-submit" id="contact-submit-btn">
                            <i class="fa fa-paper-plane-o"></i>
                            Gửi tin nhắn ngay
                        </button>

                        <div class="cf-guarantee">
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Miễn phí tư vấn</span>
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Phản hồi trong 24h</span>
                            <span class="cf-guarantee-item"><i class="fa fa-check-circle"></i> Bảo mật thông tin</span>
                        </div>
                    </form>
                </div>

                {{-- Map + Social --}}
                <div class="contact-map-col">
                    <div class="contact-map-card">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d796.1711597041572!2d108.22178662040037!3d16.032424201124755!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219ee598df9c5%3A0xaadb53409be7c909!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaeG6v24gdHLDumMgxJDDoCBO4bq1bmc!5e0!3m2!1svi!2s!4v1784109634312!5m2!1svi!2s"
                            allowfullscreen="" loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin">
                        </iframe>
                    </div>

                    {{-- Social links --}}
                    <div class="contact-social-card">
                        <h4><i class="fa fa-share-alt" style="color:var(--primary,#f15d30);margin-right:8px;"></i>Kết nối với Miu Travel</h4>
                        <div class="contact-social-links">
                            <a href="https://www.facebook.com/toantrung.tomle" target="_blank" class="contact-social-link contact-social-link--fb">
                                <i class="fa fa-facebook"></i> Facebook
                            </a>
                            <a href="#" target="_blank" class="contact-social-link contact-social-link--zl">
                                <i class="fa fa-comment"></i> Zalo
                            </a>
                            <a href="#" target="_blank" class="contact-social-link contact-social-link--yt">
                                <i class="fa fa-youtube-play"></i> YouTube
                            </a>
                        </div>
                    </div>

                    {{-- CTA call --}}
                    <a href="tel:0886733538" style="
                        display:flex; align-items:center; justify-content:center; gap:12px;
                        background: linear-gradient(135deg, var(--primary,#f15d30), #ff8c5a);
                        color:#fff; text-decoration:none; border-radius:20px;
                        padding:22px 28px; box-shadow: 0 8px 30px rgba(241,93,48,.35);
                        transition: transform .2s, box-shadow .2s;
                    " onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 14px 40px rgba(241,93,48,.45)'"
                       onmouseout="this.style.transform='';this.style.boxShadow='0 8px 30px rgba(241,93,48,.35)'">
                        <div style="width:48px;height:48px;background:rgba(255,255,255,.2);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa fa-phone" style="font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;opacity:.85;letter-spacing:1px;text-transform:uppercase;">Gọi ngay để được tư vấn</div>
                            <div style="font-size:1.5rem;font-weight:900;line-height:1.2;">0886 733 538</div>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>
@stop

@section('script')
<script>
    // Hiệu ứng loading khi submit form
    document.getElementById('contact-form').addEventListener('submit', function () {
        var btn = document.getElementById('contact-submit-btn');
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang gửi...';
        btn.disabled = true;
    });
</script>
@stop
