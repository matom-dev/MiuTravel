@extends('page.layouts.page')
@section('title', $tour->t_title)
@section('style')
<style>
:root {
    --primary: #f15d30;
    --primary-dark: #d44820;
    --primary-light: #fff3ef;
    --text-dark: #1a1a2e;
    --text-muted: #6c757d;
    --border: #e8ecf0;
    --soft-bg: #eef3f8;
    --radius: 8px;
    --shadow: 0 14px 34px rgba(26,34,54,.08);
}

.detail-hero {
    background: #123f55 !important;
    background-image: none !important;
    min-height: auto !important;
    padding: 34px 0 30px;
}
.detail-hero .overlay { display: none; }
.detail-hero .hero-inner {
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
    margin: 0 auto;
    padding: 0;
    text-align: left;
}
.detail-hero .breadcrumbs {
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.4;
    margin-bottom: 12px;
}
.detail-hero .breadcrumbs a {
    color: #fff;
    text-decoration: none;
}
.detail-hero h1 {
    color: #fff;
    font-size: clamp(1.85rem, 3vw, 2.8rem);
    font-weight: 900;
    line-height: 1.12;
    letter-spacing: 0;
    margin: 0;
}

.detail-section {
    padding: 34px 0 64px;
    background: var(--soft-bg);
}
.detail-section > .container {
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
}
.detail-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 380px;
    gap: 26px;
    align-items: start;
}
.detail-main {
    min-width: 0;
}
.content-card,
.booking-card,
.related-card {
    background: #fff;
    border: 1px solid rgba(232,236,240,.9);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}
.content-card {
    margin-bottom: 20px;
}
.content-card__header {
    padding: 20px 24px 0;
}
.content-card__body {
    padding: 22px 24px 24px;
}
.section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 0 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--primary-light);
    color: var(--primary);
    font-size: 12px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: 0;
}
.tour-location-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
    padding: 5px 11px;
    border-radius: 999px;
    background: var(--primary-light);
    color: var(--primary);
    font-size: 12px;
    font-weight: 800;
}
.tour-title {
    margin: 0 0 10px;
    color: var(--text-dark);
    font-size: clamp(1.55rem, 2.2vw, 2.2rem);
    font-weight: 900;
    line-height: 1.22;
    letter-spacing: 0;
}
.tour-status-line {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    color: var(--text-muted);
    font-size: 13.5px;
    line-height: 1.5;
}
.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    background: #dcfce7;
    color: #047857;
    font-size: 12.5px;
    font-weight: 800;
}
.status-chip--paused {
    background: #fff7ed;
    color: #c2410c;
}

.gallery-wrap { margin: 0; }
.gallery-main {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 10px;
    background: #f8fafc;
}
.gallery-main img {
    width: 100%;
    height: 430px;
    object-fit: cover;
    display: block;
    transition: opacity .2s ease;
}
.gallery-nav {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 12px;
    pointer-events: none;
}
.gallery-btn {
    pointer-events: all;
    width: 40px;
    height: 40px;
    border: 0;
    border-radius: 50%;
    background: rgba(15,23,42,.58);
    color: #fff;
    font-size: 22px;
    cursor: pointer;
}
.gallery-btn:hover { background: var(--primary); }
.gallery-counter {
    position: absolute;
    right: 14px;
    bottom: 12px;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(15,23,42,.62);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
}
.gallery-thumbs,
.album-grid {
    display: grid;
    gap: 10px;
}
.gallery-thumbs {
    grid-template-columns: repeat(auto-fill, minmax(76px, 1fr));
}
.gallery-thumb {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    border: 2px solid #dbe2ea;
    border-radius: 8px;
    cursor: pointer;
    background: #f8fafc;
}
.gallery-thumb.active { border-color: var(--primary); }
.album-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.album-photo {
    border: 0;
    padding: 0;
    border-radius: 8px;
    overflow: hidden;
    background: #f8fafc;
    aspect-ratio: 4 / 3;
    cursor: zoom-in;
}
.album-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .18s ease;
}
.album-photo:hover img { transform: scale(1.03); }

.overview-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
}
.overview-item {
    display: flex;
    gap: 11px;
    align-items: flex-start;
    min-width: 0;
    padding: 14px;
    border: 1px solid #edf1f5;
    border-radius: 8px;
    background: #fbfcfe;
}
.overview-icon {
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--primary-light);
    color: var(--primary);
}
.overview-item small {
    display: block;
    margin-bottom: 4px;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0;
}
.overview-item strong {
    display: block;
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 800;
    line-height: 1.45;
    overflow-wrap: anywhere;
}

.price-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.price-table th {
    padding: 11px 12px;
    background: #f4f6fa;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: 0;
    text-align: left;
}
.price-table td {
    padding: 13px 12px;
    border-top: 1px solid var(--border);
    color: var(--text-dark);
    vertical-align: top;
}
.price-table td:last-child {
    color: var(--primary);
    font-weight: 900;
    white-space: nowrap;
}
.price-table small {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    line-height: 1.5;
    margin-top: 2px;
}

.rich-content {
    color: #394150;
    font-size: 15px;
    line-height: 1.85;
}
.rich-content h2,
.rich-content h3 {
    color: var(--text-dark);
    font-weight: 850;
    line-height: 1.35;
}
.rich-content h2 {
    font-size: 1.22rem;
    margin: 24px 0 12px;
    padding-left: 12px;
    border-left: 4px solid var(--primary);
}
.rich-content h3 {
    font-size: 1.05rem;
    margin: 18px 0 10px;
}
.rich-content p { margin-bottom: 12px; }
.rich-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}
.empty-soft {
    margin: 0;
    padding: 16px;
    border-radius: 8px;
    background: #f8fafc;
    color: var(--text-muted);
    font-size: 14px;
    line-height: 1.6;
}

.guide-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}
.guide-card {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fbfcfe;
}
.guide-avatar {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    overflow: hidden;
    flex: 0 0 58px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 900;
    font-size: 20px;
}
.guide-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.guide-info strong {
    display: block;
    color: var(--text-dark);
    font-size: 15px;
    line-height: 1.3;
}
.guide-info span {
    display: block;
    margin: 3px 0 7px;
    color: var(--primary);
    font-size: 12.5px;
    font-weight: 800;
}
.guide-info p {
    margin: 0;
    color: var(--text-muted);
    font-size: 12.8px;
    line-height: 1.55;
}

.comment-form textarea {
    width: 100%;
    min-height: 110px;
    resize: vertical;
    padding: 14px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    outline: none;
    color: var(--text-dark);
    font-size: 14px;
    line-height: 1.6;
}
.comment-form textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(241,93,48,.1);
}
.comment-checkin-box {
    margin-top: 12px;
    padding: 13px 14px;
    border: 1.5px dashed #ffd2c4;
    border-radius: 8px;
    background: #fff8f5;
}
.comment-checkin-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    color: var(--primary-dark);
    font-size: 13px;
    font-weight: 850;
    cursor: pointer;
}
.comment-checkin-label input { display: none; }
.comment-checkin-hint {
    display: block;
    margin-top: 5px;
    color: var(--text-muted);
    font-size: 12px;
}
.comment-image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}
.comment-image-preview img {
    width: 72px;
    height: 72px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,.12);
}
.btn-comment-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
    padding: 12px 24px;
    border: 0;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    font-size: 14px;
    font-weight: 850;
    cursor: pointer;
}

.sidebar-sticky {
    position: sticky;
    top: 24px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.booking-card__top {
    padding: 20px 22px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}
.booking-card__top .bc-label {
    margin-bottom: 4px;
    color: rgba(255,255,255,.82);
    font-size: 11px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: 0;
}
.booking-card__top .bc-price {
    color: #fff;
    font-size: 1.75rem;
    font-weight: 900;
    line-height: 1.1;
}
.booking-card__top .bc-price span {
    font-size: 13px;
    font-weight: 500;
    opacity: .86;
}
.booking-card__body { padding: 20px 22px; }
.mini-info {
    display: flex;
    flex-direction: column;
    gap: 11px;
    margin-bottom: 18px;
}
.mini-info-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #475569;
    font-size: 13.5px;
    line-height: 1.5;
}
.mini-info-row .mi-icon {
    width: 16px;
    color: var(--primary);
    margin-top: 3px;
}
.mini-info-row strong {
    display: inline-block;
    min-width: 104px;
    color: var(--text-dark);
}
.btn-book-main,
.btn-activity-main,
.btn-sold-out {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    min-height: 48px;
    padding: 12px 14px;
    border-radius: 8px;
    font-size: 14.5px;
    font-weight: 850;
    text-decoration: none;
}
.btn-book-main {
    border: 0;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    box-shadow: 0 10px 22px rgba(241,93,48,.24);
}
.btn-book-main:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}
.btn-activity-main {
    margin-bottom: 10px;
    border: 1.5px solid var(--primary);
    background: #fff;
    color: var(--primary);
}
.btn-activity-main:hover {
    background: var(--primary-light);
    color: var(--primary-dark);
    text-decoration: none;
}
.btn-sold-out {
    background: #eef1f5;
    color: #64748b;
}
.hotline-mini {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 14px;
    padding: 14px 16px;
    border-radius: 8px;
    background: var(--primary-light);
}
.hotline-mini i {
    color: var(--primary);
    font-size: 18px;
}
.hotline-mini small {
    display: block;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}
.hotline-mini strong {
    color: var(--text-dark);
    font-size: 15px;
    font-weight: 900;
}
.related-card__header {
    padding: 16px 18px 0;
}
.related-card__header h4 {
    margin: 0 0 10px;
    color: var(--primary);
    font-size: 13px;
    font-weight: 850;
    text-transform: uppercase;
    letter-spacing: 0;
}
.related-list { padding: 0 16px 16px; }
.related-item {
    display: flex;
    gap: 12px;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
}
.related-item:hover { text-decoration: none; opacity: .78; }
.related-item img {
    width: 64px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
}
.related-item strong {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: var(--text-dark);
    font-size: 13px;
    line-height: 1.4;
}
.related-item span {
    display: block;
    margin-top: 4px;
    color: var(--primary);
    font-size: 12px;
    font-weight: 850;
}

@media (max-width: 1199px) {
    .overview-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .album-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 991px) {
    .detail-grid { grid-template-columns: 1fr; }
    .sidebar-sticky { position: static; }
    .gallery-main img { height: 300px; }
}
@media (max-width: 640px) {
    .detail-section > .container { width: min(100% - 24px, 1480px); }
    .content-card__header { padding: 18px 18px 0; }
    .content-card__body { padding: 18px; }
    .overview-grid,
    .guide-grid,
    .album-grid { grid-template-columns: 1fr; }
    .price-table { font-size: 13px; }
    .price-table td:last-child { white-space: normal; }
}
</style>
@stop
@section('seo')@stop
@section('content')
@php
    $tourGuides = $tour->t_guides ?: [];
    $leadGuide = $tourGuides[0] ?? null;
    $albumImages = $tour->t_anbum_image ? $tour->t_anbum_image : [];
    $mainImage = $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png');
    $allImages = $tour->t_image ? array_merge([$tour->t_image], $albumImages) : $albumImages;
    $allImageUrls = [];
    foreach ($allImages as $imgName) {
        $allImageUrls[] = asset(pare_url_file($imgName));
    }
    if (empty($allImageUrls)) {
        $allImageUrls[] = $mainImage;
    }

    $adultPrice = $tour->t_price_adults - ($tour->t_price_adults * $tour->t_sale / 100);
    $childPrice = $tour->t_price_children - ($tour->t_price_children * $tour->t_sale / 100);
    $child6Price = $childPrice * 50 / 100;
    $child2Price = $childPrice * 25 / 100;
    $durationText = $tour->duration_text;
    $publicStatusClass = $tour->is_bookable ? 'status-chip' : 'status-chip status-chip--paused';
@endphp

<section class="detail-hero">
    <div class="overlay"></div>
    <div class="hero-inner">
        <div class="breadcrumbs">
            <a href="{{ route('page.home') }}">Trang chủ</a>
            <i class="fa fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
            <a href="{{ route('tour') }}">Tours</a>
            <i class="fa fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
            Chi tiết tour
        </div>
        <h1>{{ $tour->t_title }}</h1>
    </div>
</section>

<section class="detail-section">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-main">
                <div class="content-card">
                    <div class="content-card__header">
                        @if(isset($tour->location))
                            <div class="tour-location-badge"><i class="fa fa-map-marker"></i> {{ $tour->location->l_name }}</div>
                        @endif
                        <h2 class="tour-title">{{ $tour->t_title }}</h2>
                        <div class="tour-status-line">
                            <span class="{{ $publicStatusClass }}"><i class="{{ $tour->public_status_icon }}"></i> {{ $tour->status_label }}</span>
                            <span><i class="fa fa-calendar-check-o"></i> Khách tự chọn ngày khởi hành mong muốn</span>
                        </div>
                    </div>
                    <div class="content-card__body">
                        <div class="gallery-wrap" id="album">
                            <div class="gallery-main">
                                <img id="gallery-main-img"
                                     src="{{ $mainImage }}"
                                     alt="{{ $tour->t_title }}"
                                     class="js-public-gallery"
                                     data-public-images='@json($allImageUrls)'
                                     data-public-index="0"
                                     data-public-title="{{ $tour->t_title }}"
                                     title="Bấm để xem ảnh lớn">
                                @if(count($allImageUrls) > 1)
                                    <div class="gallery-nav">
                                        <button type="button" class="gallery-btn" onclick="galleryPrev()" aria-label="Ảnh trước">&#8249;</button>
                                        <button type="button" class="gallery-btn" onclick="galleryNext()" aria-label="Ảnh sau">&#8250;</button>
                                    </div>
                                    <div class="gallery-counter"><span id="gallery-counter">1</span> / {{ count($allImageUrls) }}</div>
                                @endif
                            </div>
                            @if(count($allImageUrls) > 1)
                                <div class="gallery-thumbs">
                                    @foreach($allImageUrls as $i => $thumbUrl)
                                        <img src="{{ $thumbUrl }}" alt="Ảnh tour {{ $i + 1 }}" class="gallery-thumb {{ $i == 0 ? 'active' : '' }}" onclick="galleryGoTo({{ $i }})">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="content-card" id="overview">
                    <div class="content-card__body">
                        <div class="section-label"><i class="fa fa-info-circle"></i> Tổng quan tour</div>
                        <div class="overview-grid">
                            <div class="overview-item">
                                <div class="overview-icon"><i class="fa fa-road"></i></div>
                                <div><small>Hành trình</small><strong>{{ $tour->t_journeys ?: 'Đang cập nhật' }}</strong></div>
                            </div>
                            <div class="overview-item">
                                <div class="overview-icon"><i class="fa fa-clock-o"></i></div>
                                <div><small>Thời gian</small><strong>{{ $durationText }}</strong></div>
                            </div>
                            <div class="overview-item">
                                <div class="overview-icon"><i class="fa fa-bus"></i></div>
                                <div><small>Phương tiện</small><strong>{{ $tour->t_move_method ?: 'Đang cập nhật' }}</strong></div>
                            </div>
                            <div class="overview-item">
                                <div class="overview-icon"><i class="fa fa-map-pin"></i></div>
                                <div><small>Điểm khởi hành</small><strong>{{ $tour->t_starting_gate ?: 'Sẽ xác nhận khi tư vấn' }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card" id="prices">
                    <div class="content-card__body">
                        <div class="section-label"><i class="fa fa-tags"></i> Bảng giá theo nhóm tuổi</div>
                        <table class="price-table">
                            <thead>
                                <tr>
                                    <th>Nhóm khách</th>
                                    <th>Quy định áp dụng</th>
                                    <th>Giá / người</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Người lớn</td>
                                    <td>Trên 12 tuổi</td>
                                    <td>{{ number_format($adultPrice, 0, ',', '.') }} ₫</td>
                                </tr>
                                <tr>
                                    <td>Trẻ em</td>
                                    <td>Từ 6 đến 12 tuổi</td>
                                    <td>{{ number_format($childPrice, 0, ',', '.') }} ₫</td>
                                </tr>
                                <tr>
                                    <td>Trẻ nhỏ</td>
                                    <td>Từ 2 đến 6 tuổi<small>Tính 50% giá trẻ em.</small></td>
                                    <td>{{ number_format($child6Price, 0, ',', '.') }} ₫</td>
                                </tr>
                                <tr>
                                    <td>Sơ sinh</td>
                                    <td>Dưới 2 tuổi<small>Tính 25% giá trẻ em.</small></td>
                                    <td>{{ number_format($child2Price, 0, ',', '.') }} ₫</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="content-card" id="intro">
                    <div class="content-card__body rich-content">
                        <div class="section-label"><i class="fa fa-star"></i> Giới thiệu tour</div>
                        @if(trim(strip_tags((string) $tour->t_content)) !== '')
                            {!! $tour->t_content !!}
                        @else
                            <p class="empty-soft">Nội dung tổng quan tour đang được cập nhật.</p>
                        @endif
                    </div>
                </div>

                <div class="content-card" id="schedule">
                    <div class="content-card__body rich-content">
                        <div class="section-label"><i class="fa fa-list-alt"></i> Lịch trình chi tiết từng ngày</div>
                        @if(trim(strip_tags((string) $tour->t_description)) !== '')
                            {!! $tour->t_description !!}
                        @else
                            <p class="empty-soft">Lịch trình chi tiết từng ngày đang được cập nhật theo chương trình tour {{ $durationText }}.</p>
                        @endif
                    </div>
                </div>

                <div class="content-card" id="guides">
                    <div class="content-card__body">
                        <div class="section-label"><i class="fa fa-user-circle-o"></i> Hướng dẫn viên phụ trách</div>
                        @if(count($tourGuides) > 0)
                            <div class="guide-grid">
                                @foreach($tourGuides as $guide)
                                    <div class="guide-card">
                                        <div class="guide-avatar">
                                            @if(!empty($guide['photo']))
                                                <img src="{{ asset(pare_url_file($guide['photo'])) }}" alt="{{ $guide['name'] ?? 'Hướng dẫn viên' }}" loading="lazy">
                                            @else
                                                {{ mb_substr($guide['name'] ?? 'H', 0, 1, 'UTF-8') }}
                                            @endif
                                        </div>
                                        <div class="guide-info">
                                            <strong>{{ $guide['name'] ?? 'Đang cập nhật' }}</strong>
                                            <span>{{ $guide['role'] ?? 'Hướng dẫn viên' }}</span>
                                            @if(!empty($guide['experience']))
                                                <p><i class="fa fa-briefcase"></i> {{ $guide['experience'] }}</p>
                                            @endif
                                            @if(!empty($guide['languages']))
                                                <p><i class="fa fa-language"></i> {{ $guide['languages'] }}</p>
                                            @endif
                                            @if(!empty($guide['phone']))
                                                <p><i class="fa fa-phone"></i> {{ $guide['phone'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="empty-soft">Hướng dẫn viên phụ trách sẽ được Miu Travel xác nhận sau khi chốt ngày khởi hành mong muốn.</p>
                        @endif
                    </div>
                </div>

                <div class="content-card">
                    <div class="content-card__body">
                        <div class="section-label"><i class="fa fa-picture-o"></i> Album ảnh</div>
                        @if(count($allImageUrls) > 0)
                            <div class="album-grid">
                                @foreach($allImageUrls as $i => $imageUrl)
                                    <button type="button"
                                            class="album-photo js-public-gallery"
                                            data-public-images='@json($allImageUrls)'
                                            data-public-index="{{ $i }}"
                                            data-public-title="{{ $tour->t_title }}">
                                        <img src="{{ $imageUrl }}" alt="Album ảnh {{ $tour->t_title }} {{ $i + 1 }}" loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="empty-soft">Album ảnh tour đang được cập nhật.</p>
                        @endif
                    </div>
                </div>

                <div class="content-card" id="comments">
                    <div class="content-card__body">
                        <div class="section-label"><i class="fa fa-comments"></i> Đánh giá và bình luận ({{ $tour->comments->count() }})</div>

                        <ul class="comment-list">
                            @if($tour->comments->count() > 0)
                                @foreach($tour->comments as $comment)
                                    @include('page.common.itemComment', compact('comment'))
                                @endforeach
                            @endif
                        </ul>
                        @if($tour->comments->count() === 0)
                            <p class="empty-soft" style="text-align:center;">Chưa có bình luận nào cho tour này.</p>
                        @endif

                        @if(Auth::guard('users')->check())
                            <div class="comment-form" style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
                                <div class="section-label"><i class="fa fa-edit"></i> Viết bình luận</div>
                                <form action="#" enctype="multipart/form-data">
                                    <textarea id="message" placeholder="Chia sẻ đánh giá hoặc trải nghiệm của bạn về tour này..."></textarea>
                                    <div class="comment-checkin-box">
                                        <label class="comment-checkin-label" for="checkin_images">
                                            <i class="fa fa-camera"></i>
                                            Thêm ảnh check-in
                                            <input type="file" id="checkin_images" name="checkin_images[]" accept="image/jpeg,image/png,image/webp" multiple>
                                        </label>
                                        <span class="comment-checkin-hint">Tối đa 5 ảnh, mỗi ảnh không quá 5MB.</span>
                                        <div class="comment-image-preview"></div>
                                    </div>
                                    <span class="text-errors-comment" style="display:none;color:#e74c3c;font-size:13px;">Vui lòng nhập nội dung bình luận!</span>
                                    <button type="button" class="btn-comment-submit btn-comment" tour_id="{{ $tour->id }}">
                                        <i class="fa fa-paper-plane"></i> Gửi bình luận
                                    </button>
                                </form>
                            </div>
                        @else
                            <div style="margin-top:16px;padding:14px 18px;background:#f8f9fc;border-radius:8px;text-align:center;font-size:14px;color:var(--text-muted);">
                                <i class="fa fa-lock" style="color:var(--primary);margin-right:6px;"></i>
                                Bạn cần <a href="{{ route('page.user.account') }}" style="color:var(--primary);font-weight:700;">đăng nhập</a> để bình luận
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="sidebar-sticky">
                <div class="booking-card">
                    <div class="booking-card__top">
                        <div class="bc-label">Giá từ</div>
                        <div class="bc-price">
                            {{ number_format($adultPrice, 0, ',', '.') }}
                            <span>₫ / người lớn</span>
                        </div>
                        @if($tour->t_sale > 0)
                            <div style="font-size:12px;color:rgba(255,255,255,.75);margin-top:4px;text-decoration:line-through;">
                                Giá gốc: {{ number_format($tour->t_price_adults, 0, ',', '.') }} ₫
                            </div>
                            <div style="display:inline-block;background:rgba(255,255,255,.2);color:#fff;font-size:11px;font-weight:800;padding:3px 8px;border-radius:999px;margin-top:5px;">
                                Giảm {{ $tour->t_sale }}%
                            </div>
                        @endif
                    </div>
                    <div class="booking-card__body">
                        <div class="mini-info">
                            <div class="mini-info-row">
                                <i class="fa fa-clock-o mi-icon"></i>
                                <div><strong>Thời gian:</strong> {{ $durationText }}</div>
                            </div>
                            <div class="mini-info-row">
                                <i class="fa fa-road mi-icon"></i>
                                <div><strong>Hành trình:</strong> {{ $tour->t_journeys ?: 'Đang cập nhật' }}</div>
                            </div>
                            <div class="mini-info-row">
                                <i class="fa fa-bus mi-icon"></i>
                                <div><strong>Phương tiện:</strong> {{ $tour->t_move_method ?: 'Đang cập nhật' }}</div>
                            </div>
                            <div class="mini-info-row">
                                <i class="fa fa-map-pin mi-icon"></i>
                                <div><strong>Điểm khởi hành:</strong> {{ $tour->t_starting_gate ?: 'Sẽ xác nhận' }}</div>
                            </div>
                            <div class="mini-info-row">
                                <i class="fa fa-calendar-check-o mi-icon"></i>
                                <div><strong>Lịch khởi hành:</strong> Không áp ngày đi cố định</div>
                            </div>
                        </div>

                        <a href="{{ route('tour.activities', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}" class="btn-activity-main">
                            <i class="fa fa-list-ul"></i> Xem hoạt động & HDV
                        </a>

                        @if($tour->is_bookable)
                            <a href="{{ route('book.tour', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}" class="btn-book-main">
                                <i class="fa fa-calendar-plus-o"></i> Đặt tour theo ngày mong muốn
                            </a>
                        @else
                            <span class="btn-sold-out"><i class="{{ $tour->public_status_icon }}"></i> {{ $tour->status_label }}</span>
                            <div style="font-size:12.5px;color:#64748b;line-height:1.5;margin-top:8px;">
                                Tour vẫn có thể xem thông tin, nhưng chưa mở nhận booking mới.
                            </div>
                        @endif

                        <div class="hotline-mini">
                            <i class="fa fa-phone-square"></i>
                            <div>
                                <small>Tư vấn lịch trình</small>
                                <strong>0886 733 538</strong>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tours->count() > 0)
                    <div class="related-card">
                        <div class="related-card__header">
                            <h4><i class="fa fa-compass" style="margin-right:6px;"></i> Tour liên quan</h4>
                        </div>
                        <div class="related-list">
                            @foreach($tours as $relTour)
                                @if($relTour->is_publicly_visible)
                                    <a href="{{ route('tour.detail', ['id' => $relTour->id, 'slug' => safeTitle($relTour->t_title)]) }}" class="related-item">
                                        <img src="{{ $relTour->t_image ? asset(pare_url_file($relTour->t_image)) : asset('admin/dist/img/no-image.png') }}"
                                             alt="{{ $relTour->t_title }}"
                                             loading="lazy">
                                        <div style="flex:1;min-width:0;">
                                            <strong>{{ $relTour->t_title }}</strong>
                                            <span>{{ number_format($relTour->t_price_adults - ($relTour->t_price_adults * $relTour->t_sale / 100), 0, ',', '.') }} ₫</span>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>

@stop
@section('script')
<script>
var galleryImages = @json($allImageUrls ?? []);
var galleryIndex = 0;

function galleryGoTo(i) {
    if (!galleryImages.length) return;
    galleryIndex = i;
    var img = document.getElementById('gallery-main-img');
    if (!img) return;
    img.style.opacity = '0';
    setTimeout(function() {
        img.src = galleryImages[i];
        img.setAttribute('data-public-index', i);
        img.style.opacity = '1';
        var counter = document.getElementById('gallery-counter');
        if (counter) counter.textContent = i + 1;
    }, 120);
    document.querySelectorAll('.gallery-thumb').forEach(function(el, idx) {
        el.classList.toggle('active', idx === i);
    });
}

function galleryNext() {
    galleryGoTo((galleryIndex + 1) % galleryImages.length);
}

function galleryPrev() {
    galleryGoTo((galleryIndex - 1 + galleryImages.length) % galleryImages.length);
}
</script>
@stop
