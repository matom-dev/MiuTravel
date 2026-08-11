@extends('page.layouts.page')
@section('title', 'Dịch vụ du lịch | Miu Travel')
@section('style')
<style>
.service-page {
    background: #f4f8fb;
    color: #102033;
}
.service-wrap {
    margin: 0 auto;
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
}
.service-banner .container {
    width: min(100% - 44px, 1480px);
    max-width: 1480px;
}
.service-hero {
    background: linear-gradient(180deg, #fff 0%, #f4f8fb 100%);
    padding: 54px 0 50px;
}
.service-hero-grid {
    align-items: center;
    display: grid;
    gap: 42px;
    grid-template-columns: minmax(0, .92fr) minmax(360px, .68fr);
}
.service-kicker {
    color: #f15d30;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
}
.service-title {
    color: #102033;
    font-size: clamp(2rem, 3.2vw, 3.65rem);
    font-weight: 950;
    letter-spacing: 0;
    line-height: 1.03;
    margin: 12px 0 14px;
}
.service-lead {
    color: #5f6f83;
    font-size: 17px;
    line-height: 1.8;
    margin: 0;
    max-width: 840px;
}
.service-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 24px;
}
.service-btn {
    align-items: center;
    border-radius: 8px;
    display: inline-flex;
    gap: 9px;
    font-size: 14px;
    font-weight: 850;
    min-height: 46px;
    padding: 12px 18px;
    text-decoration: none;
}
.service-btn-primary {
    background: #f15d30;
    color: #fff;
    box-shadow: 0 12px 26px rgba(241,93,48,.24);
}
.service-btn-primary:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}
.service-btn-outline {
    background: #fff;
    border: 1px solid #d9e3ec;
    color: #102033;
}
.service-btn-outline:hover {
    border-color: #f15d30;
    color: #f15d30;
    text-decoration: none;
}
.service-hero-points {
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    margin-top: 26px;
    max-width: 860px;
}
.service-point {
    background: #fff;
    border: 1px solid #e3ebf2;
    border-radius: 8px;
    box-shadow: 0 14px 34px rgba(15,23,42,.05);
    padding: 14px;
}
.service-point strong {
    color: #102033;
    display: block;
    font-size: 20px;
    font-weight: 950;
    line-height: 1.1;
}
.service-point span {
    color: #66758a;
    display: block;
    font-size: 12.5px;
    font-weight: 750;
    line-height: 1.45;
    margin-top: 6px;
}
.service-hero-card {
    background: #102033;
    border: 1px solid rgba(16,32,51,.12);
    border-radius: 8px;
    box-shadow: 0 26px 70px rgba(15,23,42,.18);
    min-height: 470px;
    overflow: hidden;
    position: relative;
}
.service-hero-photo {
    background-image: url({{ asset('page/images/services-1.jpg') }});
    background-position: center;
    background-size: cover;
    inset: 0;
    position: absolute;
    transition: transform .28s ease;
}
.service-hero-photo::after {
    background: linear-gradient(180deg, rgba(16,32,51,.08) 0%, rgba(16,32,51,.42) 42%, rgba(16,32,51,.92) 100%);
    content: "";
    inset: 0;
    position: absolute;
}
.service-hero-card:hover .service-hero-photo {
    transform: scale(1.035);
}
.service-hero-badge {
    align-items: center;
    background: rgba(255,255,255,.93);
    border-radius: 8px;
    color: #102033;
    display: inline-flex;
    font-size: 13px;
    font-weight: 900;
    gap: 8px;
    left: 18px;
    padding: 10px 12px;
    position: absolute;
    top: 18px;
    z-index: 1;
}
.service-hero-badge i {
    color: #f15d30;
}
.service-hero-body {
    bottom: 0;
    padding: 22px;
    position: absolute;
    width: 100%;
    z-index: 1;
}
.service-hero-body h2 {
    color: #fff;
    font-size: 24px;
    font-weight: 900;
    line-height: 1.22;
    margin: 0 0 10px;
}
.service-hero-body p {
    color: rgba(255,255,255,.78);
    font-size: 14px;
    line-height: 1.65;
    margin: 0;
}
.hero-mini-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    margin-top: 16px;
}
.hero-mini {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 8px;
    padding: 12px;
}
.hero-mini strong {
    color: #fff;
    display: block;
    font-size: 15px;
    font-weight: 900;
}
.hero-mini span {
    color: rgba(255,255,255,.7);
    display: block;
    font-size: 12px;
    line-height: 1.4;
    margin-top: 4px;
}
.service-section {
    padding: 44px 0;
}
.service-section + .service-section {
    padding-top: 20px;
}
.service-section-head {
    align-items: end;
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
}
.service-section-title {
    color: #102033;
    font-size: clamp(1.7rem, 2.1vw, 2.45rem);
    font-weight: 950;
    letter-spacing: 0;
    line-height: 1.12;
    margin: 0;
}
.service-section-text {
    color: #637287;
    font-size: 15px;
    line-height: 1.75;
    margin: 8px 0 0;
    max-width: 760px;
}
.service-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.service-card {
    background: #fff;
    border: 1px solid #e5edf4;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    min-height: 100%;
    overflow: hidden;
    text-decoration: none;
    transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
}
.service-card:hover {
    border-color: rgba(241,93,48,.42);
    box-shadow: 0 18px 42px rgba(15,23,42,.1);
    text-decoration: none;
    transform: translateY(-2px);
}
.service-card-photo {
    aspect-ratio: 16 / 10;
    background-position: center;
    background-size: cover;
    overflow: hidden;
    position: relative;
}
.service-card-photo::after {
    background: linear-gradient(180deg, rgba(16,32,51,0) 20%, rgba(16,32,51,.62) 100%);
    content: "";
    inset: 0;
    position: absolute;
}
.service-card-photo span {
    background: rgba(255,255,255,.92);
    border-radius: 8px;
    bottom: 12px;
    color: #102033;
    display: inline-flex;
    font-size: 12px;
    font-weight: 900;
    left: 12px;
    padding: 7px 10px;
    position: absolute;
    z-index: 1;
}
.service-card:hover .service-card-photo {
    filter: saturate(1.08) contrast(1.03);
}
.service-card-body {
    display: flex;
    flex: 1;
    flex-direction: column;
    padding: 18px;
}
.service-card-icon {
    align-items: center;
    border-radius: 8px;
    color: #fff;
    display: inline-flex;
    font-size: 16px;
    height: 38px;
    justify-content: center;
    margin-bottom: 12px;
    width: 38px;
}
.service-card h3 {
    color: #102033;
    font-size: 19px;
    font-weight: 900;
    line-height: 1.25;
    margin: 0 0 8px;
}
.service-card p {
    color: #66758a;
    flex: 1;
    font-size: 14px;
    line-height: 1.65;
    margin: 0;
}
.service-card-body > span:last-child {
    color: #f15d30;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 900;
    margin-top: 14px;
}
.service-card--tour .service-card-icon { background: #f15d30; }
.service-card--hotel .service-card-icon { background: #0f766e; }
.service-card--car .service-card-icon { background: #2563eb; }
.service-card--experience .service-card-icon { background: #d97706; }
.flex-model {
    background-image:
        linear-gradient(90deg, rgba(16,32,51,.97), rgba(18,63,85,.88)),
        url({{ asset('page/images/background.jpg') }});
    background-position: center;
    background-size: cover;
    color: #fff;
    overflow: hidden;
    position: relative;
}
.flex-model .service-section-title,
.flex-model .service-section-text {
    color: #fff;
}
.flex-model .service-section-text {
    color: rgba(255,255,255,.74);
}
.model-grid {
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.model-step {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 8px;
    box-shadow: 0 18px 40px rgba(0,0,0,.12);
    min-height: 190px;
    padding: 18px;
}
.model-step-top {
    align-items: center;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
}
.model-step-number {
    color: #ffb199;
    font-size: 13px;
    font-weight: 950;
    letter-spacing: .08em;
}
.model-step-icon {
    align-items: center;
    background: rgba(241,93,48,.95);
    border-radius: 8px;
    color: #fff;
    display: inline-flex;
    height: 42px;
    justify-content: center;
    width: 42px;
}
.model-step h3 {
    color: #fff;
    font-size: 18px;
    font-weight: 900;
    margin: 0 0 8px;
}
.model-step p {
    color: rgba(255,255,255,.75);
    font-size: 14px;
    line-height: 1.6;
    margin: 0;
}
.featured-shell {
    display: grid;
    gap: 18px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
.featured-panel {
    background: #fff;
    border: 1px solid #e5edf4;
    border-radius: 8px;
    box-shadow: 0 18px 42px rgba(15,23,42,.06);
    overflow: hidden;
}
.featured-panel-head {
    align-items: center;
    background: linear-gradient(180deg, #fff 0%, #fbfdff 100%);
    border-bottom: 1px solid #edf2f7;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 15px 16px;
}
.featured-panel-head h3 i {
    color: #f15d30;
    margin-right: 7px;
}
.featured-panel-head h3 {
    color: #102033;
    font-size: 17px;
    font-weight: 900;
    margin: 0;
}
.featured-panel-head a {
    color: #f15d30;
    font-size: 12.5px;
    font-weight: 850;
}
.featured-list {
    display: grid;
    gap: 0;
}
.featured-item {
    align-items: center;
    border-bottom: 1px solid #edf2f7;
    color: inherit;
    display: grid;
    gap: 12px;
    grid-template-columns: 86px minmax(0, 1fr);
    padding: 13px 16px;
    text-decoration: none;
}
.featured-item:last-child {
    border-bottom: 0;
}
.featured-item:hover {
    background: #f8fbfc;
    text-decoration: none;
}
.featured-thumb {
    aspect-ratio: 4 / 3;
    background-position: center;
    background-size: cover;
    border-radius: 8px;
}
.featured-item h4 {
    color: #102033;
    font-size: 14px;
    font-weight: 900;
    line-height: 1.35;
    margin: 0 0 5px;
}
.featured-item p {
    color: #6b7787;
    font-size: 12.5px;
    line-height: 1.45;
    margin: 0;
}
.featured-meta {
    color: #f15d30;
    display: inline-block;
    font-size: 12.5px;
    font-weight: 850;
    margin-top: 6px;
}
.service-empty {
    color: #8a97a8;
    font-size: 14px;
    padding: 24px 16px;
    text-align: center;
}
.support-band {
    background:
        linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,.02)),
        #fff;
    padding-bottom: 58px;
}
.support-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: 1.1fr .9fr;
}
.support-copy {
    background-image:
        linear-gradient(90deg, rgba(16,32,51,.94), rgba(18,63,85,.84)),
        url({{ asset('page/images/introduce_4.jpg') }});
    background-position: center;
    background-size: cover;
    border: 0;
    border-radius: 8px;
    overflow: hidden;
    padding: 30px;
    position: relative;
}
.support-copy h2 {
    color: #fff;
    font-size: clamp(1.55rem, 2vw, 2.25rem);
    font-weight: 950;
    line-height: 1.16;
    margin: 0 0 10px;
}
.support-copy p {
    color: rgba(255,255,255,.76);
    font-size: 15px;
    line-height: 1.75;
    margin: 0;
}
.support-copy .service-kicker {
    color: #ffb199;
}
.support-list {
    display: grid;
    gap: 10px;
}
.support-item {
    align-items: center;
    background: #fff;
    border: 1px solid #e5edf4;
    border-radius: 8px;
    box-shadow: 0 14px 34px rgba(15,23,42,.05);
    display: flex;
    gap: 12px;
    padding: 15px;
}
.support-item i {
    align-items: center;
    background: rgba(241,93,48,.1);
    border-radius: 8px;
    color: #f15d30;
    display: inline-flex;
    height: 38px;
    justify-content: center;
    width: 38px;
}
.support-item strong {
    color: #102033;
    display: block;
    font-size: 14px;
}
.support-item span {
    color: #66758a;
    display: block;
    font-size: 12.5px;
    margin-top: 2px;
}
@media (max-width: 991px) {
    .service-hero-grid,
    .support-grid {
        grid-template-columns: 1fr;
    }
    .service-grid,
    .model-grid,
    .featured-shell {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .service-section-head {
        align-items: flex-start;
        flex-direction: column;
    }
    .service-hero-points {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 575px) {
    .service-wrap {
        width: min(100% - 24px, 1480px);
    }
    .service-grid,
    .model-grid,
    .featured-shell {
        grid-template-columns: 1fr;
    }
    .service-actions {
        flex-direction: column;
    }
    .service-btn {
        justify-content: center;
        width: 100%;
    }
    .service-hero-card {
        min-height: 440px;
    }
    .hero-mini-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@stop

@section('content')
<main class="service-page">
    <section class="service-banner miu-page-banner">
        <div class="container">
            <p class="breadcrumbs">
                <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                <span>Dịch vụ</span>
            </p>
            <h1 class="miu-page-banner-title">Dịch vụ Miu Travel</h1>
        </div>
    </section>

    <section class="service-hero">
        <div class="service-wrap">
            <div class="service-hero-grid">
                <div>
                    <span class="service-kicker"><i class="fa fa-compass"></i> Đi theo ngày bạn muốn</span>
                    <h1 class="service-title">Từ chọn tour đến chuẩn bị chuyến đi, mọi thứ rõ ràng hơn.</h1>
                    <p class="service-lead">
                        Miu Travel tập trung vào mô hình tour linh hoạt: khách chọn chương trình phù hợp, tự chọn ngày
                        khởi hành mong muốn, hệ thống tính ngày về dự kiến và đội ngũ tư vấn xác nhận lại lịch trình
                        trước khi chốt booking.
                    </p>
                    <div class="service-actions">
                        <a href="{{ route('tour') }}" class="service-btn service-btn-primary">
                            <i class="fa fa-map-signs"></i> Chọn tour
                        </a>
                        <a href="{{ route('about.us') }}#lien-he" class="service-btn service-btn-outline">
                            <i class="fa fa-phone"></i> Tư vấn lịch trình
                        </a>
                    </div>
                    <div class="service-hero-points">
                        <div class="service-point">
                            <strong>Tự chọn</strong>
                            <span>Ngày khởi hành theo kế hoạch của khách.</span>
                        </div>
                        <div class="service-point">
                            <strong>Tự tính</strong>
                            <span>Ngày về dự kiến theo thời gian tour.</span>
                        </div>
                        <div class="service-point">
                            <strong>Xác nhận</strong>
                            <span>Miu Travel liên hệ trước khi chốt booking.</span>
                        </div>
                    </div>
                </div>
                <div class="service-hero-card">
                    <div class="service-hero-photo"></div>
                    <div class="service-hero-badge"><i class="fa fa-calendar-check-o"></i> Tour theo ngày mong muốn</div>
                    <div class="service-hero-body">
                        <h2>Tour có thời gian rõ ràng, ngày đi do bạn chọn</h2>
                        <p>
                            Ví dụ tour 3 ngày 2 đêm: bạn chọn ngày khởi hành, hệ thống tự tính ngày về dự kiến. Miu
                            Travel liên hệ xác nhận lại trước khi hoàn tất booking.
                        </p>
                        <div class="hero-mini-grid">
                            <div class="hero-mini">
                                <strong>3 ngày 2 đêm</strong>
                                <span>Thời gian chương trình tour</span>
                            </div>
                            <div class="hero-mini">
                                <strong>Ngày về dự kiến</strong>
                                <span>Hiển thị ngay khi đặt tour</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section">
        <div class="service-wrap">
            <div class="service-section-head">
                <div>
                    <span class="service-kicker"><i class="fa fa-briefcase"></i> Nhóm dịch vụ chính</span>
                    <h2 class="service-section-title">Một hệ sinh thái cho chuyến đi du lịch</h2>
                    <p class="service-section-text">
                        Trang dịch vụ được chia theo đúng nhu cầu của khách: chọn tour, chọn nơi ở, chọn phương tiện và
                        tham khảo kinh nghiệm/hoạt động trước chuyến đi.
                    </p>
                </div>
            </div>

            <div class="service-grid">
                <a href="{{ route('tour') }}" class="service-card service-card--tour">
                    <div class="service-card-photo" style="background-image:url({{ asset('page/images/services-1.jpg') }});">
                        <span>Tour linh hoạt</span>
                    </div>
                    <div class="service-card-body">
                        <span class="service-card-icon"><i class="fa fa-map-signs"></i></span>
                        <h3>Tour linh hoạt</h3>
                        <p>Chọn hành trình, thời gian tour và ngày khởi hành mong muốn. Hệ thống tính ngày về dự kiến khi đặt.</p>
                        <span>Xem chi tiết <i class="fa fa-angle-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('hotel') }}" class="service-card service-card--hotel">
                    <div class="service-card-photo" style="background-image:url({{ asset('page/images/services-2.jpg') }});">
                        <span>Lưu trú phù hợp</span>
                    </div>
                    <div class="service-card-body">
                        <span class="service-card-icon"><i class="fa fa-building"></i></span>
                        <h3>Khách sạn</h3>
                        <p>Tham khảo nơi lưu trú theo điểm đến và liên hệ trực tiếp khách sạn để xác nhận phòng.</p>
                        <span>Xem chi tiết <i class="fa fa-angle-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('car.rental') }}" class="service-card service-card--car">
                    <div class="service-card-photo" style="background-image:url({{ asset('page/images/services-3.jpg') }});">
                        <span>Di chuyển chủ động</span>
                    </div>
                    <div class="service-card-body">
                        <span class="service-card-icon"><i class="fa fa-car"></i></span>
                        <h3>Thuê xe</h3>
                        <p>Chọn xe theo số chỗ, cung đường, thời gian sử dụng và điểm đón phù hợp với lịch trình.</p>
                        <span>Xem chi tiết <i class="fa fa-angle-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('articles.category', 'kinh-nghiem-du-lich') }}" class="service-card service-card--experience">
                    <div class="service-card-photo" style="background-image:url({{ asset('page/images/services-4.jpg') }});">
                        <span>Gợi ý trải nghiệm</span>
                    </div>
                    <div class="service-card-body">
                        <span class="service-card-icon"><i class="fa fa-star"></i></span>
                        <h3>Kinh nghiệm du lịch</h3>
                        <p>Tham khảo điểm đến, hoạt động nổi bật, thời điểm nên đi và những lưu ý trước khi khởi hành.</p>
                        <span>Xem chi tiết <i class="fa fa-angle-right"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="service-section flex-model">
        <div class="service-wrap">
            <div class="service-section-head">
                <div>
                    <span class="service-kicker"><i class="fa fa-calendar-check-o"></i> Quy trình đặt tour</span>
                    <h2 class="service-section-title">Mô hình đặt tour theo ngày khách chọn</h2>
                    <p class="service-section-text">
                        Miu Travel không áp ngày đi cố định trên tour. Chương trình tour có thời gian rõ ràng, còn ngày
                        khởi hành được chọn theo kế hoạch của khách.
                    </p>
                </div>
            </div>
            <div class="model-grid">
                <div class="model-step">
                    <div class="model-step-top">
                        <div class="model-step-number">01</div>
                        <div class="model-step-icon"><i class="fa fa-map-o"></i></div>
                    </div>
                    <h3>Chọn chương trình tour</h3>
                    <p>Xem điểm đến, thời gian, giá người lớn/trẻ em, lịch trình chi tiết và trạng thái còn nhận đặt.</p>
                </div>
                <div class="model-step">
                    <div class="model-step-top">
                        <div class="model-step-number">02</div>
                        <div class="model-step-icon"><i class="fa fa-calendar-plus-o"></i></div>
                    </div>
                    <h3>Chọn ngày khởi hành</h3>
                    <p>Khách nhập ngày đi mong muốn theo kế hoạch cá nhân, gia đình hoặc nhóm của mình.</p>
                </div>
                <div class="model-step">
                    <div class="model-step-top">
                        <div class="model-step-number">03</div>
                        <div class="model-step-icon"><i class="fa fa-calculator"></i></div>
                    </div>
                    <h3>Tự tính ngày về dự kiến</h3>
                    <p>Hệ thống dựa trên thời gian tour, ví dụ 3 ngày 2 đêm, để tính ngày về cho booking.</p>
                </div>
                <div class="model-step">
                    <div class="model-step-top">
                        <div class="model-step-number">04</div>
                        <div class="model-step-icon"><i class="fa fa-check-circle-o"></i></div>
                    </div>
                    <h3>Xác nhận trước khi chốt</h3>
                    <p>Miu Travel liên hệ lại để xác nhận lịch trình, số khách, điểm đón và tổng tiền tạm tính.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="service-section">
        <div class="service-wrap">
            <div class="service-section-head">
                <div>
                    <span class="service-kicker"><i class="fa fa-lightbulb-o"></i> Gợi ý hiện có</span>
                    <h2 class="service-section-title">Các lựa chọn hỗ trợ cho chuyến đi</h2>
                    <p class="service-section-text">
                        Sau khi chọn tour và ngày đi, khách có thể tham khảo thêm nơi lưu trú và xe di chuyển để hoàn
                        thiện lịch trình.
                    </p>
                </div>
            </div>

            <div class="featured-shell">
                <div class="featured-panel">
                    <div class="featured-panel-head">
                        <h3><i class="fa fa-building"></i> Khách sạn tham khảo</h3>
                        <a href="{{ route('hotel') }}">Xem tất cả</a>
                    </div>
                    <div class="featured-list">
                        @forelse($hotels as $hotel)
                            @php $hotelImage = $hotel->h_image ? asset(pare_url_file($hotel->h_image)) : asset('admin/dist/img/no-image.png'); @endphp
                            <a href="{{ route('hotel.detail', ['id' => $hotel->id, 'slug' => safeTitle($hotel->h_name)]) }}" class="featured-item">
                                <div class="featured-thumb" style="background-image:url({{ $hotelImage }});"></div>
                                <div>
                                    <h4>{{ $hotel->h_name }}</h4>
                                    <p>{{ the_excerpt(strip_tags($hotel->h_description ?? 'Thông tin lưu trú đang được cập nhật.'), 86) }}</p>
                                    <span class="featured-meta"><i class="fa fa-phone"></i> Liên hệ trực tiếp khách sạn</span>
                                </div>
                            </a>
                        @empty
                            <div class="service-empty">Khách sạn đang được cập nhật.</div>
                        @endforelse
                    </div>
                </div>

                <div class="featured-panel">
                    <div class="featured-panel-head">
                        <h3><i class="fa fa-car"></i> Thuê xe</h3>
                        <a href="{{ route('car.rental') }}">Xem tất cả</a>
                    </div>
                    <div class="featured-list">
                        @forelse($carRentals as $carRental)
                            @php $carImage = $carRental->cr_image ? asset(pare_url_file($carRental->cr_image)) : asset('admin/dist/img/no-image.png'); @endphp
                            <a href="{{ route('car.rental.detail', ['id' => $carRental->id, 'slug' => safeTitle($carRental->cr_name)]) }}" class="featured-item">
                                <div class="featured-thumb" style="background-image:url({{ $carImage }});"></div>
                                <div>
                                    <h4>{{ $carRental->cr_name }}</h4>
                                    <p>{{ the_excerpt(strip_tags($carRental->cr_description ?? 'Thông tin xe đang được cập nhật.'), 86) }}</p>
                                    <span class="featured-meta">
                                        {{ $carRental->cr_number_seats ? $carRental->cr_number_seats . ' chỗ' : 'Số chỗ đang cập nhật' }} · Liên hệ báo giá
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="service-empty">Dịch vụ thuê xe đang được cập nhật.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="service-section support-band">
        <div class="service-wrap">
            <div class="support-grid">
                <div class="support-copy">
                    <span class="service-kicker"><i class="fa fa-headphones"></i> Hỗ trợ trước khi đi</span>
                    <h2>Cần lịch trình riêng hoặc đi theo nhóm?</h2>
                    <p>
                        Gửi nhu cầu về điểm đến, ngày đi mong muốn, số khách và ngân sách. Miu Travel sẽ tư vấn phương
                        án phù hợp, sau đó xác nhận lại lịch trình trước khi chốt booking.
                    </p>
                    <div class="service-actions">
                        <a href="{{ route('about.us') }}#lien-he" class="service-btn service-btn-primary">
                            <i class="fa fa-paper-plane-o"></i> Gửi yêu cầu tư vấn
                        </a>
                    </div>
                </div>
                <div class="support-list">
                    <div class="support-item">
                        <i class="fa fa-calendar-plus-o"></i>
                        <div><strong>Ngày đi linh hoạt</strong><span>Khách chủ động chọn ngày khởi hành mong muốn.</span></div>
                    </div>
                    <div class="support-item">
                        <i class="fa fa-calculator"></i>
                        <div><strong>Tổng tiền tạm tính</strong><span>Hiển thị theo số khách và bảng giá từng nhóm tuổi.</span></div>
                    </div>
                    <div class="support-item">
                        <i class="fa fa-check-circle"></i>
                        <div><strong>Xác nhận rõ ràng</strong><span>Booking được Miu Travel liên hệ xác nhận trước khi chốt.</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@stop
