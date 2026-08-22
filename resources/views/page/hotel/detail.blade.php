@extends('page.layouts.page')
@section('title', $hotel->h_name . ' | Miu Travel')
@section('style')
<style>
/* ══════════════════════════════════════════
   HOTEL DETAIL PAGE
══════════════════════════════════════════ */

/* ── Hero compact ── */
.hotel-detail-hero {
    background: #123f55 !important;
    background-image: none !important;
    min-height: auto !important;
    padding: 34px 0 30px !important;
    position: relative;
    display: flex;
    align-items: flex-start;
}
.hotel-detail-hero .overlay {
    display: none;
}
.hotel-detail-hero .hero-inner {
    position: relative; z-index: 2;
    padding: 0;
    width: 100%;
}

.hotel-detail-hero > .container {
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
}

/* ── Breadcrumb ── */
.hd-breadcrumb { list-style:none; margin:0 0 10px; padding:0; display:flex; gap:6px; align-items:center; }
.hd-breadcrumb li { font-size:13px; color:rgba(255,255,255,.7); }
.hd-breadcrumb li a { color:rgba(255,255,255,.85); text-decoration:none; }
.hd-breadcrumb li a:hover { color:#fff; }
.hd-breadcrumb li i { font-size:9px; margin-left:6px; }

/* ── Layout ── */
.hotel-detail-body { padding: 48px 0 64px; background:#f8fafc; }
.hotel-detail-layout { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 32px; align-items: start; }
.hotel-detail-main { min-width: 0; }
@media (max-width: 991px) { .hotel-detail-layout { grid-template-columns: 1fr; } }

/* ══════════════
   MAIN COLUMN
══════════════ */

/* Gallery */
.hd-gallery { background:#fff; border-radius:20px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.07); margin-bottom:24px; }
.hd-gallery__main { position:relative; aspect-ratio: 16 / 9; background:#f1f5f9; overflow:hidden; }
.hd-gallery__main img {
    width:100%; height:100%; object-fit:cover;
    transition: opacity 0.3s ease;
    display: block;
}
.hd-gallery__nav {
    position:absolute; top:50%; transform:translateY(-50%);
    width:100%; display:flex; justify-content:space-between;
    padding: 0 14px; pointer-events:none;
}
.hd-gallery__nav-btn {
    pointer-events:all;
    width:42px; height:42px; border-radius:50%;
    background:rgba(255,255,255,.92);
    border:none; cursor:pointer; font-size:20px;
    display:flex; align-items:center; justify-content:center;
    color:#1a202c; box-shadow:0 2px 12px rgba(0,0,0,.18);
    transition: background 0.2s, transform 0.2s;
}
.hd-gallery__nav-btn:hover { background:#fff; transform:scale(1.08); }
.hd-gallery__counter {
    position:absolute; bottom:14px; right:16px;
    background:rgba(0,0,0,.55); color:#fff;
    font-size:12px; font-weight:600;
    padding:4px 12px; border-radius:20px; backdrop-filter:blur(4px);
}
.hd-gallery__thumbs {
    display:flex; gap:8px; padding:12px 14px 14px;
    overflow-x:auto;
}
.hd-gallery__thumbs::-webkit-scrollbar { height:4px; }
.hd-gallery__thumbs::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:2px; }
.hd-gallery__thumb {
    width:84px; height:62px; object-fit:cover;
    border-radius:8px; border:2.5px solid #e2e8f0;
    cursor:pointer; flex-shrink:0;
    transition: border-color 0.2s, transform 0.2s;
}
.hd-gallery__thumb:hover { transform: scale(1.05); }
.hd-gallery__thumb.active { border-color: var(--primary, #e84c00); }

/* Single image */
.hd-single-img {
    width:100%; height:auto; max-height:440px;
    border-radius:20px; display:block; object-fit:cover;
    margin-bottom:24px; box-shadow:0 4px 24px rgba(0,0,0,.1);
}

/* Info cards */
.hd-section-card {
    background:#fff; border-radius:20px;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    padding:28px 30px;
    margin-bottom:24px;
    overflow:hidden;
}
.hd-section-title {
    font-size:17px; font-weight:800; color:#1a202c;
    margin-bottom:20px; display:flex; align-items:center; gap:10px;
}
.hd-section-title .icon-badge {
    width:34px; height:34px; border-radius:10px;
    background:linear-gradient(135deg,#ff6b2b,#e84c00);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:15px; flex-shrink:0;
}

/* Info list */
.hd-info-list { list-style:none; margin:0; padding:0; display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media(max-width:575px){ .hd-info-list{ grid-template-columns:1fr; } }
.hd-info-item {
    display:flex; align-items:flex-start; gap:12px;
    padding:14px 16px; background:#f8fafc;
    border-radius:12px; border:1px solid #f1f5f9;
}
.hd-info-item__icon {
    width:36px; height:36px; border-radius:10px;
    background:#fff0ea; display:flex; align-items:center;
    justify-content:center; color:var(--primary,#e84c00); font-size:16px; flex-shrink:0;
}
.hd-info-item__label { font-size:11px; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:2px; }
.hd-info-item__value { font-size:14px; font-weight:700; color:#1a202c; line-height:1.4; }
.hd-feature-block + .hd-feature-block { margin-top:20px; }
.hd-feature-block h3 { color:#344054; font-size:13px; font-weight:800; margin:0 0 10px; }
.hd-feature-tags { display:flex; flex-wrap:wrap; gap:8px; }
.hd-feature-tag {
    align-items:center; background:#f8fafc; border:1px solid #e2e8f0; border-radius:7px;
    color:#475467; display:inline-flex; font-size:13px; font-weight:650; gap:7px; padding:8px 11px;
}
.hd-feature-tag i { color:var(--primary,#e84c00); }
.hd-official-stars { color:#f5a000; white-space:nowrap; }

/* Description / content */
.hd-content-body {
    font-size:15px; color:#475569; line-height:1.75;
    overflow-wrap:anywhere;
}
.hd-content-body h1,.hd-content-body h2,.hd-content-body h3 { color:#1a202c; margin-top:20px; }
.hd-content-body img {
    display:block;
    width:auto !important;
    max-width:100% !important;
    height:auto !important;
    max-height:420px !important;
    object-fit:contain !important;
    border-radius:10px;
    margin:16px auto;
}
.hd-content-body figure {
    max-width:100% !important;
    width:auto !important;
    margin:16px 0;
    overflow:hidden;
}
.hd-content-body iframe,
.hd-content-body table {
    max-width:100%;
}

@media (max-width: 575px) {
    .hd-gallery__main { aspect-ratio: 4 / 3; }
    .hd-single-img { max-height:280px; }
    .hd-content-body img { max-height:280px !important; }
    .hd-section-card { padding:22px 18px; border-radius:16px; }
}

/* ══════════════
   COMMENTS
══════════════ */
.hd-comments-title {
    font-size:17px; font-weight:800; color:#1a202c;
    margin-bottom:20px; padding-bottom:14px;
    border-bottom:2px solid #f1f5f9;
}
.hd-comment-form {
    background: linear-gradient(135deg,#fff8f5,#fff);
    border:1.5px solid #fde8df;
    border-radius:16px; padding:24px;
}
.hd-comment-form label {
    font-size:13px; font-weight:700; color:#64748b;
    text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; display:block;
}
.hd-comment-form textarea {
    width:100%; border:2px solid #e8edf2; border-radius:12px;
    padding:14px 16px; font-size:14px; color:#1a202c;
    background:#f8fafc; resize:vertical; min-height:120px;
    outline:none; transition:border-color 0.25s, box-shadow 0.25s;
    font-family: inherit;
}
.hd-comment-form textarea:focus {
    border-color:var(--primary,#e84c00);
    background:#fff; box-shadow:0 0 0 4px rgba(232,76,0,.08);
}
.hd-map-frame {
    width: 100%;
    min-height: 280px;
    border: 0;
    border-radius: 12px;
    overflow: hidden;
    background: #eef2f7;
}
.hd-rating-row {
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
}
.hd-rating-stars {
    display:inline-flex;
    flex-direction:row-reverse;
    gap:4px;
}
.hd-rating-stars input {
    position:absolute;
    opacity:0;
    pointer-events:none;
}
.hd-rating-stars label {
    color:#d7dee8;
    cursor:pointer;
    font-size:18px;
    line-height:1;
    margin:0;
}
.hd-rating-stars input:checked ~ label,
.hd-rating-stars label:hover,
.hd-rating-stars label:hover ~ label {
    color:#f7c94b;
}
.comment-checkin-box {
    padding:13px 14px;
    border:1.5px dashed #ffd2c4;
    border-radius:12px;
    background:#fff8f5;
}
.comment-checkin-label {
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin:0;
    color:#c84a24;
    font-size:13px;
    font-weight:850;
    cursor:pointer;
}
.comment-checkin-label input { display:none; }
.comment-checkin-hint {
    display:block;
    margin-top:5px;
    color:#64748b;
    font-size:12px;
}
.comment-image-preview {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:10px;
}
.comment-image-preview img {
    width:72px;
    height:72px;
    border-radius:8px;
    object-fit:cover;
    border:2px solid #fff;
    box-shadow:0 4px 14px rgba(0,0,0,.12);
}
.hd-comment-btn {
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 28px;
    background:linear-gradient(135deg,#ff6b2b,#e84c00);
    color:#fff; border:none; border-radius:50px;
    font-size:14px; font-weight:700; cursor:pointer;
    box-shadow:0 4px 16px rgba(232,76,0,.3);
    transition: transform 0.2s, box-shadow 0.2s;
}
.hd-comment-btn:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(232,76,0,.4); }

/* ══════════════
   SIDEBAR
══════════════ */
.hd-sidebar { position: sticky; top: 24px; }

/* Direct hotel contact card */
.hd-booking-card {
    background:#fff; border-radius:20px;
    box-shadow:0 8px 40px rgba(0,0,0,.1);
    overflow:hidden; margin-bottom:24px;
    border:1px solid rgba(0,0,0,.05);
}
.hd-booking-card__header {
    background:#123f55;
    padding:24px 24px 20px; text-align:center;
}
.hd-contact-icon {
    align-items:center; background:rgba(255,255,255,.14); border-radius:50%; color:#fff;
    display:inline-flex; font-size:20px; height:46px; justify-content:center; margin-bottom:10px; width:46px;
}
.hd-contact-eyebrow {
    color:rgba(255,255,255,.78); font-size:11px; font-weight:800; text-transform:uppercase; margin-bottom:4px;
}
.hd-contact-title { color:#fff; font-size:20px; font-weight:900; line-height:1.25; margin:0; }
.hd-contact-copy { color:rgba(255,255,255,.78); font-size:12.5px; line-height:1.55; margin:8px 0 0; }
.hd-stay-request {
    background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; margin-bottom:16px; padding:14px;
}
.hd-stay-request__title { color:#1d2939; font-size:13px; font-weight:800; margin-bottom:9px; }
.hd-stay-request__grid { display:grid; gap:8px; grid-template-columns:1fr 1fr; }
.hd-stay-request__item { color:#667085; font-size:11px; line-height:1.4; }
.hd-stay-request__item strong { color:#1d2939; display:block; font-size:13px; margin-top:2px; }
.hd-booking-card__body { padding:22px 24px; }

/* Quick info in sidebar */
.hd-quick-info { list-style:none; margin:0 0 20px; padding:0; display:flex; flex-direction:column; gap:10px; }
.hd-quick-info li {
    display:flex; align-items:center; gap:12px;
    font-size:13.5px; color:#475569; padding:10px 14px;
    background:#f8fafc; border-radius:10px;
}
.hd-quick-info li i { color:var(--primary,#e84c00); font-size:15px; width:18px; text-align:center; }
.hd-quick-info li strong { color:#1a202c; }

/* CTA buttons */
.hd-cta-primary {
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; padding:14px;
    background:linear-gradient(135deg,#ff6b2b,#e84c00);
    color:#fff; border:none; border-radius:12px;
    font-size:15px; font-weight:700; cursor:pointer; text-decoration:none;
    box-shadow:0 4px 16px rgba(232,76,0,.3);
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom:10px;
}
.hd-cta-primary:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(232,76,0,.4); color:#fff; text-decoration:none; }
.hd-phone-unavailable {
    background:#f2f4f7; border-radius:10px; color:#667085; font-size:13px; font-weight:700;
    padding:13px; text-align:center;
}
/* Related hotels card */
.hd-related-card {
    background:#fff; border-radius:20px;
    box-shadow:0 4px 20px rgba(0,0,0,.07);
    overflow:hidden;
}
.hd-related-card__header {
    padding:18px 22px 16px;
    border-bottom:1px solid #f1f5f9;
    font-size:15px; font-weight:800; color:#1a202c;
    display:flex; align-items:center; gap:9px;
}
.hd-related-card__header i { color:var(--primary,#e84c00); }
.hd-related-card__body { padding:12px 14px 14px; display:flex; flex-direction:column; gap:4px; }
</style>
@stop
@section('seo')
@php
    $hotelSeoDescription = the_excerpt(strip_tags($hotel->h_description ?: $hotel->h_content ?: $hotel->h_name), 155);
    $hotelSeoImage = $hotel->h_image ? asset(pare_url_file($hotel->h_image)) : asset('admin/dist/img/no-image.png');
    $hotelSeoUrl = route('hotel.detail', ['id' => $hotel->id, 'slug' => safeTitle($hotel->h_name)]);
    $hotelSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Hotel',
        'name' => $hotel->h_name,
        'description' => $hotelSeoDescription,
        'image' => $hotelSeoImage,
        'url' => $hotelSeoUrl,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $hotel->h_address,
        ],
    ];

    if ($hotel->h_star_rating) {
        $hotelSchema['starRating'] = [
            '@type' => 'Rating',
            'ratingValue' => (int) $hotel->h_star_rating,
            'bestRating' => 5,
        ];
    }
@endphp
<meta name="description" content="{{ $hotelSeoDescription }}">
<link rel="canonical" href="{{ $hotelSeoUrl }}">
<meta property="og:type" content="place">
<meta property="og:title" content="{{ $hotel->h_name }} | Miu Travel">
<meta property="og:description" content="{{ $hotelSeoDescription }}">
<meta property="og:image" content="{{ $hotelSeoImage }}">
<meta property="og:url" content="{{ $hotelSeoUrl }}">
<meta name="twitter:card" content="summary_large_image">
<script type="application/ld+json">{!! json_encode($hotelSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@stop

@section('content')

{{-- ── Hero ── --}}
<div class="hotel-detail-hero">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-inner">
            <ul class="hd-breadcrumb">
                <li><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></li>
                <li><a href="{{ route('hotel') }}">Khách sạn <i class="fa fa-chevron-right"></i></a></li>
                <li>{{ the_excerpt($hotel->h_name, 50) }}</li>
            </ul>
            <h1 class="hotel-detail-title">{{ $hotel->h_name }}</h1>
            @if($hotel->h_address)
                <p class="hotel-detail-location">
                    <i class="fa fa-map-marker"></i> {{ $hotel->h_address }}
                </p>
            @endif
        </div>
    </div>
</div>

{{-- ── Body ── --}}
<div class="hotel-detail-body">
    <div class="container">
        <div class="hotel-detail-layout">

            {{-- ════════════════ MAIN ════════════════ --}}
            <div class="hotel-detail-main">

                @php
                    $albumImages  = $hotel->h_anbum_image ? $hotel->h_anbum_image : [];
                    $mainImage    = $hotel->h_image ? asset(pare_url_file($hotel->h_image)) : asset('admin/dist/img/no-image.png');
                    $allImages    = $hotel->h_image ? array_merge([$hotel->h_image], $albumImages) : $albumImages;
                    $allImageUrls = [];
                    foreach($allImages as $imgName) { $allImageUrls[] = asset(pare_url_file($imgName)); }
                @endphp

                {{-- Gallery --}}
                @if(count($allImages) > 1)
                    <div class="hd-gallery">
                        <div class="hd-gallery__main">
                            <img id="hd-main-img"
                                 src="{{ $mainImage }}"
                                 alt="{{ $hotel->h_name }}"
                                 class="js-public-gallery"
                                 data-public-images='@json($allImageUrls)'
                                 data-public-index="0"
                                 data-public-title="{{ $hotel->h_name }}"
                                 title="Bấm để xem ảnh lớn">
                            <div class="hd-gallery__nav">
                                <button class="hd-gallery__nav-btn" onclick="hdGalleryPrev()">&#8249;</button>
                                <button class="hd-gallery__nav-btn" onclick="hdGalleryNext()">&#8250;</button>
                            </div>
                            <div class="hd-gallery__counter">
                                <span id="hd-gallery-counter">1</span> / {{ count($allImages) }}
                            </div>
                        </div>
                        <div class="hd-gallery__thumbs">
                            @foreach($allImages as $i => $imgName)
                                <img src="{{ asset(pare_url_file($imgName)) }}"
                                     alt=""
                                     class="hd-gallery__thumb {{ $i === 0 ? 'active' : '' }}"
                                     onclick="hdGalleryGoTo({{ $i }})">
                            @endforeach
                        </div>
                    </div>
                    <script>
                        var hdImages = @json($allImageUrls);
                        var hdIndex  = 0;
                        function hdGalleryGoTo(i) {
                            hdIndex = i;
                            var img = document.getElementById('hd-main-img');
                            img.style.opacity = '0';
                            setTimeout(function(){
                                img.src = hdImages[i];
                                img.setAttribute('data-public-index', i);
                                img.style.opacity = '1';
                                document.getElementById('hd-gallery-counter').textContent = i + 1;
                            }, 150);
                            document.querySelectorAll('.hd-gallery__thumb').forEach(function(el, idx){
                                el.classList.toggle('active', idx === i);
                            });
                        }
                        function hdGalleryNext() { hdGalleryGoTo((hdIndex + 1) % hdImages.length); }
                        function hdGalleryPrev() { hdGalleryGoTo((hdIndex - 1 + hdImages.length) % hdImages.length); }
                    </script>
                @else
                    <img src="{{ $mainImage }}"
                         alt="{{ $hotel->h_name }}"
                         class="hd-single-img js-public-gallery"
                         data-public-images='@json($allImageUrls)'
                         data-public-index="0"
                         data-public-title="{{ $hotel->h_name }}"
                         title="Bấm để xem ảnh lớn">
                @endif

                {{-- Thông tin liên hệ --}}
                <div class="hd-section-card">
                    <div class="hd-section-title">
                        <div class="icon-badge"><i class="fa fa-info"></i></div>
                        Thông tin liên hệ
                    </div>
                    <ul class="hd-info-list">
                        <li class="hd-info-item">
                            <div class="hd-info-item__icon"><i class="fa fa-building"></i></div>
                            <div>
                                <div class="hd-info-item__label">Loại hình lưu trú</div>
                                <div class="hd-info-item__value">{{ \App\Models\Hotel::ACCOMMODATION_TYPES[$hotel->h_accommodation_type] ?? 'Khách sạn' }}</div>
                            </div>
                        </li>
                        @if($hotel->h_star_rating)
                        <li class="hd-info-item">
                            <div class="hd-info-item__icon"><i class="fa fa-star"></i></div>
                            <div>
                                <div class="hd-info-item__label">Hạng khách sạn</div>
                                <div class="hd-info-item__value hd-official-stars" aria-label="{{ $hotel->h_star_rating }} sao">{{ str_repeat('★', $hotel->h_star_rating) }}</div>
                            </div>
                        </li>
                        @endif
                        @if($hotel->h_address)
                        <li class="hd-info-item">
                            <div class="hd-info-item__icon"><i class="fa fa-map-marker"></i></div>
                            <div>
                                <div class="hd-info-item__label">Địa chỉ</div>
                                <div class="hd-info-item__value">{{ $hotel->h_address }}</div>
                            </div>
                        </li>
                        @endif
                        @if($hotel->h_phone)
                        <li class="hd-info-item">
                            <div class="hd-info-item__icon"><i class="fa fa-phone"></i></div>
                            <div>
                                <div class="hd-info-item__label">Điện thoại</div>
                                <div class="hd-info-item__value">{{ $hotel->h_phone }}</div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>

                @if(!empty($hotel->h_amenities) || !empty($hotel->h_suitable_for))
                <div class="hd-section-card">
                    <div class="hd-section-title">
                        <div class="icon-badge"><i class="fa fa-check"></i></div>
                        Thông tin lưu trú đã xác minh
                    </div>
                    @if(!empty($hotel->h_amenities))
                        <div class="hd-feature-block">
                            <h3>Tiện nghi và đặc điểm</h3>
                            <div class="hd-feature-tags">
                                @foreach($hotel->h_amenities as $amenity)
                                    @if(isset(\App\Models\Hotel::AMENITIES[$amenity]))
                                        <span class="hd-feature-tag"><i class="fa fa-check-circle"></i>{{ \App\Models\Hotel::AMENITIES[$amenity] }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if(!empty($hotel->h_suitable_for))
                        <div class="hd-feature-block">
                            <h3>Phù hợp với</h3>
                            <div class="hd-feature-tags">
                                @foreach($hotel->h_suitable_for as $group)
                                    @if(isset(\App\Models\Hotel::SUITABLE_FOR[$group]))
                                        <span class="hd-feature-tag"><i class="fa fa-users"></i>{{ \App\Models\Hotel::SUITABLE_FOR[$group] }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                {{-- Nội dung --}}
                @if($hotel->h_content || $hotel->h_description)
                <div class="hd-section-card">
                    <div class="hd-section-title">
                        <div class="icon-badge"><i class="fa fa-file-text-o"></i></div>
                        Thông tin khách sạn
                    </div>
                    <div class="hd-content-body">{!! $hotel->h_content ?: $hotel->h_description !!}</div>
                </div>
                @endif

                @if(!empty($mapQuery))
                <div class="hd-section-card">
                    <div class="hd-section-title">
                        <div class="icon-badge"><i class="fa fa-map-marker"></i></div>
                        Bản đồ khu vực
                    </div>
                    <iframe
                        class="hd-map-frame"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q={{ urlencode($mapQuery) }}&output=embed"
                        title="Bản đồ {{ $hotel->h_name }}"></iframe>
                </div>
                @endif

                {{-- Bình luận --}}
                <div class="hd-section-card">
                    <h3 class="hd-comments-title">
                        <i class="fa fa-comments" style="color:var(--primary);margin-right:8px;"></i>
                        Đánh giá & Bình luận
                        @if($hotel->comments->count() > 0)
                            <span style="font-size:13px;font-weight:500;color:#94a3b8;margin-left:8px;">({{ $hotel->comments->count() }} bình luận)</span>
                        @endif
                    </h3>

                    <ul class="comment-list" style="padding:0;margin-bottom:28px;">
                        @if ($hotel->comments->count() > 0)
                            @foreach($hotel->comments as $key => $comment)
                                @include('page.common.itemComment', compact('comment'))
                            @endforeach
                        @else
                            <div style="text-align:center;padding:32px 0;color:#94a3b8;">
                                <i class="fa fa-comment-o" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:10px;"></i>
                                <p style="margin:0;">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
                            </div>
                        @endif
                    </ul>

                    @if (Auth::guard('users')->check())
                        <form action="#" class="hd-comment-form" enctype="multipart/form-data">
                            <label for="hd-comment-msg">
                                <i class="fa fa-pencil" style="color:var(--primary);"></i>
                                Viết bình luận của bạn
                            </label>
                            <div class="hd-rating-row">
                                <span style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;">Điểm sao</span>
                                <div class="hd-rating-stars" aria-label="Chọn điểm đánh giá">
                                    @for($star = 5; $star >= 1; $star--)
                                        <input type="radio" id="hotel-rating-{{ $star }}" name="rating" value="{{ $star }}" {{ $star === 5 ? 'checked' : '' }}>
                                        <label for="hotel-rating-{{ $star }}"><i class="fa fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <textarea id="hd-comment-msg" name="message" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về khách sạn này..."></textarea>
                            <div class="comment-checkin-box" style="margin-top:12px;">
                                <label class="comment-checkin-label" for="hotel_checkin_images">
                                    <i class="fa fa-camera"></i>
                                    Thêm ảnh thực tế
                                    <input type="file" id="hotel_checkin_images" name="checkin_images[]" accept="image/jpeg,image/png,image/webp" multiple>
                                </label>
                                <span class="comment-checkin-hint">Tối đa 5 ảnh, mỗi ảnh không quá 5MB.</span>
                                <div class="comment-image-preview"></div>
                            </div>
                            <span class="text-errors-comment" style="display:none;color:#ef4444;font-size:13px;margin:6px 0 0;display:block;">
                                Vui lòng nhập nội dung bình luận!
                            </span>
                            <div style="margin-top:14px;">
                                <button type="button" class="hd-comment-btn btn-comment" hotel_id="{{ $hotel->id }}">
                                    <i class="fa fa-paper-plane-o"></i> Gửi bình luận
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="text-align:center;padding:24px;background:#f8fafc;border-radius:14px;border:1.5px dashed #e2e8f0;">
                            <i class="fa fa-lock" style="font-size:1.8rem;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
                            <p style="color:#64748b;font-size:14px;margin:0 0 14px;">Bạn cần đăng nhập để bình luận</p>
                            <a href="{{ route('page.user.account') }}" class="hd-cta-primary" style="width:auto;padding:10px 28px;display:inline-flex;">
                                <i class="fa fa-sign-in"></i> Đăng nhập ngay
                            </a>
                        </div>
                    @endif
                </div>

            </div>{{-- end main --}}

            {{-- ════════════════ SIDEBAR ════════════════ --}}
            <aside class="hd-sidebar">

                {{-- Direct contact card --}}
                <div class="hd-booking-card">
                    <div class="hd-booking-card__header">
                        <div class="hd-contact-icon"><i class="fa fa-phone"></i></div>
                        <div class="hd-contact-eyebrow">Liên hệ trực tiếp</div>
                        <h2 class="hd-contact-title">Đặt phòng với khách sạn</h2>
                        <p class="hd-contact-copy">Lễ tân khách sạn sẽ xác nhận giá, phòng trống và chính sách lưu trú.</p>
                    </div>
                    <div class="hd-booking-card__body">

                        @if($stayContext['check_in'] && $stayContext['check_out'])
                            <div class="hd-stay-request">
                                <div class="hd-stay-request__title"><i class="fa fa-calendar"></i> Nhu cầu lưu trú của bạn</div>
                                <div class="hd-stay-request__grid">
                                    <div class="hd-stay-request__item">Nhận phòng<strong>{{ \Carbon\Carbon::parse($stayContext['check_in'])->format('d/m/Y') }}</strong></div>
                                    <div class="hd-stay-request__item">Trả phòng<strong>{{ \Carbon\Carbon::parse($stayContext['check_out'])->format('d/m/Y') }}</strong></div>
                                    <div class="hd-stay-request__item">Số khách<strong>{{ $stayContext['adults'] }} người lớn{{ $stayContext['children'] ? ', '.$stayContext['children'].' trẻ em' : '' }}</strong></div>
                                    <div class="hd-stay-request__item">Số phòng<strong>{{ $stayContext['rooms'] }} phòng</strong></div>
                                </div>
                            </div>
                        @endif

                        <ul class="hd-quick-info">
                            @if($hotel->h_address)
                            <li>
                                <i class="fa fa-map-marker"></i>
                                <div><span style="color:#94a3b8;font-size:12px;">Địa chỉ</span><br><strong>{{ $hotel->h_address }}</strong></div>
                            </li>
                            @endif
                            @if($hotel->h_phone)
                            <li>
                                <i class="fa fa-phone"></i>
                                <div><span style="color:#94a3b8;font-size:12px;">Số lễ tân</span><br><strong>{{ $hotel->h_phone }}</strong></div>
                            </li>
                            @endif
                        </ul>

                        @if($hotel->h_phone && $hotel->phone_href)
                            <a href="tel:{{ $hotel->phone_href }}" class="hd-cta-primary">
                                <i class="fa fa-phone"></i> Gọi trực tiếp lễ tân
                            </a>
                        @else
                            <div class="hd-phone-unavailable">
                                <i class="fa fa-info-circle"></i> Số điện thoại đang được cập nhật
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Related hotels --}}
                @if ($hotels->count() > 0)
                <div class="hd-related-card">
                    <div class="hd-related-card__header">
                        <i class="fa fa-building"></i> Nơi lưu trú khác
                    </div>
                    <div class="hd-related-card__body">
                        @php $itemHotel = 'item-related-tour' @endphp
                        @foreach($hotels as $hotelItem)
                            @include('page.common.itemHotel', ['hotel' => $hotelItem, 'itemHotel' => $itemHotel])
                        @endforeach
                    </div>
                </div>
                @endif

            </aside>

        </div>{{-- end layout --}}
    </div>
</div>

@stop
@section('script')
@stop
