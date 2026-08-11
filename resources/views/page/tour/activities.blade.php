@extends('page.layouts.page')
@section('title', 'Hoạt động tour | ' . $tour->t_title)
@section('style')
<style>
    :root {
        --primary: #f15d30;
        --primary-dark: #d44820;
        --text-dark: #172033;
        --text-muted: #6b7280;
        --border: #e7ebf0;
    }

    .tour-act-hero {
        position: relative;
        min-height: auto !important;
        display: flex;
        align-items: flex-start;
        background: #123f55 !important;
        background-image: none !important;
        padding: 34px 0 30px;
        overflow: hidden;
    }

    .tour-act-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: none;
    }

    .tour-act-hero__inner {
        position: relative;
        z-index: 1;
        padding: 0;
        color: #fff;
    }

    .tour-act-hero > .container {
        width: min(100% - 28px, 1480px);
        max-width: 1480px;
    }

    .tour-act-breadcrumb {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.4;
        color: #fff;
        margin-bottom: 12px;
    }

    .tour-act-breadcrumb a {
        color: #fff;
        text-decoration: none;
    }

    .tour-act-hero h1 {
        max-width: 840px;
        color: #fff;
        font-size: clamp(1.9rem, 3.1vw, 3rem);
        font-weight: 900;
        line-height: 1.12;
        margin: 0;
        letter-spacing: 0;
    }

    .tour-act-section {
        background: #f5f7fb;
        padding: 56px 0 72px;
    }

    .tour-act-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 26px;
        align-items: start;
    }

    .tour-act-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 34px rgba(15, 23, 42, .06);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .tour-act-panel__head {
        padding: 22px 24px 0;
    }

    .tour-act-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .tour-act-panel h2 {
        color: var(--text-dark);
        font-size: 1.55rem;
        font-weight: 900;
        margin: 0 0 8px;
    }

    .tour-act-panel__body {
        padding: 22px 24px 24px;
    }

    .activity-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .activity-item {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px;
        background: #fbfcff;
        min-height: 150px;
    }

    .activity-item__icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        font-size: 18px;
        margin-bottom: 14px;
    }

    .activity-item h3 {
        color: var(--text-dark);
        font-size: 1rem;
        font-weight: 850;
        margin: 0 0 8px;
    }

    .activity-item p {
        color: var(--text-muted);
        font-size: 14px;
        line-height: 1.65;
        margin: 0;
    }

    .guide-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .guide-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px;
        background: #fff;
    }

    .guide-card__top {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .guide-avatar {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff1ec;
        color: var(--primary);
        font-size: 20px;
        font-weight: 900;
        flex: 0 0 auto;
        overflow: hidden;
        border: 3px solid #fff1ec;
    }

    .guide-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .guide-card h3 {
        color: var(--text-dark);
        font-size: 1rem;
        font-weight: 850;
        margin: 0 0 2px;
    }

    .guide-card small {
        color: var(--primary);
        font-weight: 800;
    }

    .guide-info {
        display: flex;
        gap: 8px;
        color: var(--text-muted);
        font-size: 13.5px;
        line-height: 1.5;
        margin: 7px 0;
    }

    .guide-info i {
        color: var(--primary);
        width: 16px;
        margin-top: 3px;
    }

    .tour-act-side {
        position: sticky;
        top: 88px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 34px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .summary-card__image {
        height: 190px;
        background-size: cover;
        background-position: center;
    }

    .summary-card__body {
        padding: 20px;
    }

    .summary-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: var(--text-muted);
        font-size: 13.5px;
        margin-bottom: 12px;
    }

    .summary-row i {
        color: var(--primary);
        width: 16px;
        margin-top: 3px;
    }

    .summary-row strong {
        display: block;
        color: var(--text-dark);
        font-size: 13px;
    }

    .btn-act-primary,
    .btn-act-outline {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 46px;
        border-radius: 10px;
        font-weight: 850;
        text-decoration: none;
        margin-top: 10px;
    }

    .btn-act-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
    }

    .btn-act-primary:hover {
        color: #fff;
        text-decoration: none;
    }

    .btn-act-outline {
        border: 1px solid var(--border);
        color: var(--text-dark);
        background: #fff;
    }

    .btn-act-outline:hover {
        color: var(--primary);
        text-decoration: none;
    }

    .empty-note {
        padding: 24px;
        border: 1px dashed #d8dee8;
        border-radius: 12px;
        color: var(--text-muted);
        text-align: center;
        background: #fbfcff;
    }

    .related-mini {
        display: flex;
        gap: 10px;
        align-items: center;
        padding: 11px 0;
        border-bottom: 1px solid var(--border);
        color: var(--text-dark);
        text-decoration: none;
    }

    .related-mini:last-child {
        border-bottom: 0;
    }

    .related-mini img {
        width: 64px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
    }

    .related-mini strong {
        display: block;
        color: var(--text-dark);
        font-size: 13px;
        line-height: 1.35;
    }

    .related-mini span {
        color: var(--primary);
        font-size: 12px;
        font-weight: 800;
    }

    @media (max-width: 991px) {
        .tour-act-layout {
            grid-template-columns: 1fr;
        }

        .tour-act-side {
            position: static;
        }
    }

    @media (max-width: 767px) {
        .activity-list,
        .guide-grid {
            grid-template-columns: 1fr;
        }

        .tour-act-hero { min-height: auto !important; }
    }
</style>
@stop

@section('content')
@php
    $activities = $tour->t_activities ?: [];
    $guides = $tour->t_guides ?: [];
    $mainImage = $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('page/images/bg_5.jpg');
@endphp

<section class="tour-act-hero">
    <div class="container">
        <div class="tour-act-hero__inner">
            <div class="tour-act-breadcrumb">
                <a href="{{ route('page.home') }}">Trang chủ</a>
                <i class="fa fa-chevron-right" style="font-size:10px;margin:0 7px;"></i>
                <a href="{{ route('tour') }}">Tours</a>
                <i class="fa fa-chevron-right" style="font-size:10px;margin:0 7px;"></i>
                Hoạt động tour
            </div>
            <h1>{{ $tour->t_title }}</h1>
        </div>
    </div>
</section>

<section class="tour-act-section">
    <div class="container">
        <div class="tour-act-layout">
            <div>
                <div class="tour-act-panel">
                    <div class="tour-act-panel__head">
                        <div class="tour-act-label"><i class="fa fa-compass"></i> Hoạt động dịch vụ</div>
                        <h2>Những trải nghiệm có trong tour</h2>
                    </div>
                    <div class="tour-act-panel__body">
                        @if(count($activities) > 0)
                            <div class="activity-list">
                                @foreach($activities as $activity)
                                    <div class="activity-item">
                                        <div class="activity-item__icon">
                                            <i class="{{ $activity['icon'] ?? 'fa fa-check-circle' }}"></i>
                                        </div>
                                        <h3>{{ $activity['title'] ?? 'Hoạt động tour' }}</h3>
                                        <p>{{ $activity['description'] ?? 'Thông tin hoạt động đang được cập nhật.' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-note">
                                <i class="fa fa-info-circle" style="color:var(--primary);font-size:22px;margin-bottom:8px;display:block;"></i>
                                Admin chưa cập nhật hoạt động dịch vụ cho tour này.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tour-act-panel">
                    <div class="tour-act-panel__head">
                        <div class="tour-act-label"><i class="fa fa-user-circle-o"></i> Hướng dẫn viên</div>
                        <h2>Thông tin người đồng hành</h2>
                    </div>
                    <div class="tour-act-panel__body">
                        @if(count($guides) > 0)
                            <div class="guide-grid">
                                @foreach($guides as $guide)
                                    <div class="guide-card">
                                        <div class="guide-card__top">
                                            <div class="guide-avatar">
                                                @if(!empty($guide['photo']))
                                                    <img src="{{ asset(pare_url_file($guide['photo'])) }}" alt="{{ $guide['name'] ?? 'Hướng dẫn viên' }}" loading="lazy">
                                                @else
                                                    {{ mb_substr($guide['name'] ?? 'H', 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <h3>{{ $guide['name'] ?? 'Hướng dẫn viên' }}</h3>
                                                <small>{{ $guide['role'] ?? 'Hướng dẫn viên' }}</small>
                                            </div>
                                        </div>
                                        @if(!empty($guide['experience']))
                                            <div class="guide-info"><i class="fa fa-briefcase"></i><span>{{ $guide['experience'] }}</span></div>
                                        @endif
                                        @if(!empty($guide['languages']))
                                            <div class="guide-info"><i class="fa fa-language"></i><span>{{ $guide['languages'] }}</span></div>
                                        @endif
                                        @if(!empty($guide['phone']))
                                            <div class="guide-info"><i class="fa fa-phone"></i><span>{{ $guide['phone'] }}</span></div>
                                        @endif
                                        @if(!empty($guide['email']))
                                            <div class="guide-info"><i class="fa fa-envelope"></i><span>{{ $guide['email'] }}</span></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-note">
                                <i class="fa fa-info-circle" style="color:var(--primary);font-size:22px;margin-bottom:8px;display:block;"></i>
                                Admin chưa cập nhật hướng dẫn viên cho tour này.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="tour-act-side">
                <div class="summary-card">
                    <div class="summary-card__image" style="background-image:url({{ $mainImage }});"></div>
                    <div class="summary-card__body">
                        <div class="tour-act-label"><i class="fa fa-info-circle"></i> Thông tin tour</div>
                        <div class="summary-row">
                            <i class="fa fa-map-marker"></i>
                            <div><strong>Địa điểm</strong>{{ optional($tour->location)->l_name ?: 'Đang cập nhật' }}</div>
                        </div>
                        <div class="summary-row">
                            <i class="fa fa-clock-o"></i>
                            <div><strong>Thời gian</strong>{{ $tour->duration_text ?: 'Đang cập nhật' }}</div>
                        </div>
                        <div class="summary-row">
                            <i class="fa fa-bus"></i>
                            <div><strong>Phương tiện</strong>{{ $tour->t_move_method ?: 'Đang cập nhật' }}</div>
                        </div>
                        <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}" class="btn-act-outline">
                            <i class="fa fa-arrow-left"></i> Về chi tiết tour
                        </a>
                    </div>
                </div>

                @if($relatedTours->count() > 0)
                    <div class="summary-card" style="margin-top:20px;">
                        <div class="summary-card__body">
                            <div class="tour-act-label"><i class="fa fa-compass"></i> Tour liên quan</div>
                            @foreach($relatedTours as $relatedTour)
                                <a href="{{ route('tour.activities', ['id' => $relatedTour->id, 'slug' => safeTitle($relatedTour->t_title)]) }}" class="related-mini">
                                    <img src="{{ $relatedTour->t_image ? asset(pare_url_file($relatedTour->t_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $relatedTour->t_title }}">
                                    <div>
                                        <strong>{{ the_excerpt($relatedTour->t_title, 52) }}</strong>
                                        <span>Xem hoạt động</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@stop
