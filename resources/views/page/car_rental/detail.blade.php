@extends('page.layouts.page')
@section('title', $carRental->cr_name . ' | Thuê xe Miu Travel')
@section('style')
<style>
.car-detail {
    background: #f5f7fb;
    padding: 34px 0 70px;
}
.car-detail-hero {
    position: relative;
    min-height: auto !important;
    display: flex;
    align-items: flex-start;
    background: #123f55 !important;
    background-image: none !important;
    padding: 34px 0 30px;
}
.car-detail-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: none;
}
.car-detail-hero__inner {
    position: relative;
    z-index: 1;
    padding: 0;
    color: #fff;
}
.car-detail-hero > .container {
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
}
.car-detail-hero h1 {
    color: #fff;
    max-width: 850px;
    font-size: clamp(1.9rem, 3.1vw, 3rem);
    font-weight: 900;
    line-height: 1.12;
    margin: 0 0 12px;
    letter-spacing: 0;
}
.car-detail-hero p {
    color: rgba(255,255,255,.86);
    margin: 0 0 12px;
    line-height: 1.4;
}
.car-detail-hero p,
.car-detail-hero p a {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
}
.car-detail-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 24px;
}
.car-panel,
.car-side-card {
    background: #fff;
    border: 1px solid #e7ebf0;
    border-radius: 12px;
    box-shadow: 0 10px 34px rgba(15,23,42,.06);
}
.car-panel {
    padding: 22px;
}
.car-main-img {
    width: 100%;
    aspect-ratio: 16 / 10;
    object-fit: cover;
    border-radius: 10px;
    display: block;
    background: #e5e7eb;
    cursor: zoom-in;
}
.car-album {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 10px;
    margin-top: 12px;
}
.car-album img {
    width: 100%;
    aspect-ratio: 1.45;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    cursor: zoom-in;
    transition: transform .2s, box-shadow .2s;
}
.car-album img:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15,23,42,.14);
}
.car-section-title {
    margin: 24px 0 12px;
    color: #172033;
    font-size: 1.25rem;
    font-weight: 900;
}
.car-content {
    color: #475569;
    line-height: 1.75;
}
.car-side-card {
    padding: 18px;
    position: sticky;
    top: 96px;
}
.car-contact-box {
    padding: 16px;
    border-left: 4px solid #12947f;
    border-radius: 8px;
    background: #edf8f6;
    color: #172033;
    margin-bottom: 16px;
}
.car-contact-box small {
    display: block;
    color: #118874;
    font-weight: 800;
    margin-bottom: 4px;
    text-transform: uppercase;
}
.car-contact-box strong {
    display: block;
    font-size: 1.22rem;
    font-weight: 900;
    line-height: 1.35;
}
.car-contact-box p {
    color: #5f6b7b;
    font-size: 13px;
    line-height: 1.6;
    margin: 7px 0 0;
}
.car-info-list {
    display: grid;
    gap: 10px;
    margin-bottom: 16px;
}
.car-info-list div {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #475569;
    font-size: 14px;
}
.car-info-list i {
    width: 18px;
    color: #f15d30;
    margin-top: 3px;
}
.car-contact-btn,
.car-back-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 44px;
    border-radius: 9px;
    font-weight: 900;
    text-decoration: none;
}
.car-contact-btn {
    background: #e84f26;
    color: #fff;
    margin-bottom: 10px;
}
.car-back-btn {
    background: #172033;
    color: #fff;
}
.car-contact-btn:hover,
.car-back-btn:hover {
    color: #fff;
    text-decoration: none;
}
.car-contact-unavailable {
    padding: 12px;
    border: 1px solid #dfe6ed;
    border-radius: 8px;
    background: #f8fafc;
    color: #64748b;
    font-size: 13px;
    font-weight: 750;
    margin-bottom: 10px;
    text-align: center;
}
.related-car {
    display: flex;
    gap: 10px;
    padding-top: 12px;
    margin-top: 12px;
    border-top: 1px solid #eef2f6;
    color: #172033;
    text-decoration: none;
}
.related-car img {
    width: 78px;
    height: 58px;
    object-fit: cover;
    border-radius: 8px;
}
.related-car strong {
    display: block;
    font-size: 13px;
    line-height: 1.35;
}
.related-car span {
    color: #f15d30;
    font-size: 12px;
    font-weight: 850;
}
@media (max-width: 991px) {
    .car-detail-layout { grid-template-columns: 1fr; }
    .car-side-card { position: static; }
}
@media (max-width: 575px) {
    .car-panel { padding: 16px; }
    .car-album { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
</style>
@stop

@section('content')
@php
    $mainImage = $carRental->cr_image ? asset(pare_url_file($carRental->cr_image)) : asset('admin/dist/img/no-image.png');
    $album = $carRental->cr_album_images ?: [];
    $galleryImages = [$mainImage];
    foreach($album as $image) {
        $galleryImages[] = asset(pare_url_file($image));
    }
@endphp
<section class="car-detail-hero">
    <div class="container">
        <div class="car-detail-hero__inner">
            <p>
                <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                <span><a href="{{ route('car.rental') }}">Thuê xe <i class="fa fa-chevron-right"></i></a></span>
            </p>
            <h1>{{ $carRental->cr_name }}</h1>
        </div>
    </div>
</section>

<section class="car-detail">
    <div class="container">
        <div class="car-detail-layout">
            <main class="car-panel">
                <img src="{{ $mainImage }}"
                     alt="{{ $carRental->cr_name }}"
                     class="car-main-img js-public-gallery"
                     data-public-images='@json($galleryImages)'
                     data-public-index="0"
                     data-public-title="{{ $carRental->cr_name }}">
                @if(count($album))
                    <div class="car-album">
                        @foreach($album as $index => $image)
                            <img src="{{ asset(pare_url_file($image)) }}"
                                 alt="{{ $carRental->cr_name }}"
                                 class="js-public-gallery"
                                 data-public-images='@json($galleryImages)'
                                 data-public-index="{{ $index + 1 }}"
                                 data-public-title="{{ $carRental->cr_name }}">
                        @endforeach
                    </div>
                @endif

                @if($carRental->cr_content || $carRental->cr_description)
                    <h2 class="car-section-title">Thông tin dịch vụ</h2>
                    <div class="car-content">{!! $carRental->cr_content ?: $carRental->cr_description !!}</div>
                @endif
            </main>

            <aside>
                <div class="car-side-card">
                    <div class="car-contact-box">
                        <small>Liên hệ trực tiếp</small>
                        <strong>Nhận báo giá từ đơn vị cho thuê</strong>
                        <p>Đơn vị cung cấp sẽ xác nhận lịch xe, tuyến đường, tài xế và các chi phí liên quan.</p>
                    </div>

                    <div class="car-info-list">
                        <div><i class="fa fa-car"></i><span>{{ $carRental->cr_vehicle_type ?: 'Xe du lịch' }}</span></div>
                        <div><i class="fa fa-users"></i><span>{{ $carRental->cr_number_seats ? $carRental->cr_number_seats . ' chỗ' : 'Đang cập nhật số chỗ' }}</span></div>
                        <div><i class="fa fa-cog"></i><span>{{ $carRental->cr_transmission ?: 'Đang cập nhật hộp số' }}</span></div>
                        <div><i class="fa fa-tint"></i><span>{{ $carRental->cr_fuel ?: 'Đang cập nhật nhiên liệu' }}</span></div>
                        <div><i class="fa fa-map-marker"></i><span>{{ optional($carRental->location)->l_name ?: 'Linh hoạt theo yêu cầu' }}</span></div>
                        <div><i class="fa fa-location-arrow"></i><span>{{ $carRental->cr_address ?: 'Điểm nhận xe sẽ được tư vấn' }}</span></div>
                        @if($carRental->cr_phone)
                            <div><i class="fa fa-phone"></i><span>{{ $carRental->cr_phone }}</span></div>
                        @endif
                    </div>

                    @if($carRental->cr_phone && $carRental->phone_href)
                        <a href="tel:{{ $carRental->phone_href }}" class="car-contact-btn"><i class="fa fa-phone mr-2"></i> Gọi trực tiếp đơn vị</a>
                    @else
                        <div class="car-contact-unavailable"><i class="fa fa-info-circle mr-1"></i> Số điện thoại đang được cập nhật</div>
                    @endif
                    <a href="{{ route('car.rental') }}" class="car-back-btn"><i class="fa fa-arrow-left mr-2"></i> Xem xe khác</a>

                    @if($relatedCars->count())
                        <h3 class="car-section-title" style="font-size:1rem;margin-top:22px;">Dịch vụ khác</h3>
                        @foreach($relatedCars as $relatedCar)
                            <a href="{{ route('car.rental.detail', ['id' => $relatedCar->id, 'slug' => safeTitle($relatedCar->cr_name)]) }}" class="related-car">
                                <img src="{{ $relatedCar->cr_image ? asset(pare_url_file($relatedCar->cr_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $relatedCar->cr_name }}">
                                <div>
                                    <strong>{{ the_excerpt($relatedCar->cr_name, 48) }}</strong>
                                    <span>{{ $relatedCar->cr_number_seats ? $relatedCar->cr_number_seats . ' chỗ · ' : '' }}Liên hệ báo giá</span>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
@stop
