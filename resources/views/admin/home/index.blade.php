@extends('admin.layouts.main')
@section('title', 'Quản lý du lịch')
@section('style-css')
<style>
    .dashboard-card {
        border: 0;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .055) !important;
    }

    .dashboard-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f3f6;
        padding: 10px 14px;
    }

    .dashboard-card .card-title {
        color: #111827;
        font-size: 15px;
        line-height: 1.25;
    }

    .dashboard-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .dashboard-hero h1 {
        margin-bottom: 2px !important;
    }

    .dashboard-hero small {
        color: #6b7280;
        display: block;
        font-size: 12.5px;
    }

    .dashboard-hero-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .dashboard-hero-actions .btn {
        height: 34px;
        padding: 0 12px !important;
        font-size: 13px !important;
    }

    .dashboard-section-label {
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        margin: 2px 0 8px;
        text-transform: uppercase;
    }

    .overview-grid,
    .mini-stat-grid,
    .quick-action-grid {
        display: grid;
        gap: 10px;
    }

    .overview-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .mini-stat-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        margin-bottom: 16px;
    }

    .overview-tile,
    .mini-stat-tile {
        display: flex;
        align-items: center;
        gap: 10px;
        color: inherit;
        text-decoration: none;
    }

    .overview-tile {
        min-height: 92px;
        border-radius: 8px;
        padding: 12px;
        background: #fff;
        box-shadow: 0 4px 14px rgba(15, 23, 42, .055);
        border-left: 4px solid var(--tile-color, #2563eb);
        transition: transform .18s, box-shadow .18s;
    }

    .overview-tile:hover,
    .overview-tile:focus,
    .mini-stat-tile:hover,
    .mini-stat-tile:focus,
    .quick-action-card:hover,
    .quick-action-card:focus {
        color: inherit;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, .09);
    }

    .overview-icon,
    .mini-stat-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        color: var(--tile-color, #2563eb);
        background: rgba(37, 99, 235, .08);
    }

    .overview-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        font-size: 16px;
    }

    .overview-label,
    .mini-stat-label {
        color: #6b7280;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .overview-number {
        color: #111827;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.15;
        margin-top: 2px;
    }

    .overview-meta {
        color: #6b7280;
        display: block;
        font-size: 11.5px;
        margin-top: 2px;
    }

    .mini-stat-tile {
        border: 1px solid #eef2f7;
        border-radius: 8px;
        background: #fff;
        padding: 9px 10px;
        transition: transform .18s, box-shadow .18s;
    }

    .mini-stat-icon {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        font-size: 13px;
    }

    .mini-stat-number {
        color: #111827;
        display: block;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.15;
    }

    .chart-card .card-header {
        gap: 10px;
        flex-wrap: wrap;
    }

    .chart-title-group small {
        display: block;
        color: #6b7280;
        font-size: 12px;
        margin-top: 2px;
    }

    .chart-filter-form {
        gap: 8px;
    }

    .chart-filter-form .form-control,
    .chart-filter-form .btn {
        height: 32px;
        border-radius: 7px;
    }

    .revenue-chart-shell {
        position: relative;
        border: 1px solid #eef2f7;
        border-radius: 8px;
        background: #fff;
        padding: 12px 12px 4px;
    }

    .revenue-chart-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 8px;
    }

    .revenue-chart-metric {
        border: 1px solid #edf1f5;
        border-radius: 7px;
        background: #f9fafb;
        padding: 9px 10px;
    }

    .revenue-chart-metric span {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .revenue-chart-metric strong {
        display: block;
        color: #111827;
        font-size: 17px;
        line-height: 1.2;
        margin-top: 3px;
        white-space: nowrap;
    }

    .revenue-chart-metric i {
        font-size: 13px;
    }

    .revenue-chart-metric.revenue i {
        color: #059669;
    }

    .revenue-chart-metric.guests i {
        color: #2563eb;
    }

    .revenue-chart-metric.days i {
        color: #f59e0b;
    }

    #revenueGuestChart {
        min-height: 285px;
    }

    #monthlyBookingRevenueChart {
        min-height: 245px;
    }

    .quick-action-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        margin-bottom: 16px;
    }

    .quick-action-card {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 58px;
        padding: 10px;
        border: 1px solid #eef0f4;
        border-radius: 8px;
        color: #1f2937;
        background: #fff;
        text-decoration: none;
        transition: transform .18s, box-shadow .18s, border-color .18s;
    }

    .quick-action-card:hover {
        border-color: #dbe2ea;
    }

    .quick-action-card span {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex: 0 0 auto;
    }

    .quick-action-card strong {
        display: block;
        font-size: 13px;
        line-height: 1.25;
    }

    .quick-action-card small {
        color: #6b7280;
        display: block;
        font-size: 11.5px;
        line-height: 1.25;
    }

    .admin-list-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 9px 0;
        border-bottom: 1px solid #f1f3f6;
    }

    .admin-list-item:last-child {
        border-bottom: 0;
    }

    .admin-list-title {
        color: #111827;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .admin-list-meta {
        color: #6b7280;
        font-size: 12px;
        line-height: 1.35;
    }

    .status-progress-row {
        margin-bottom: 10px;
    }

    .status-progress-row:last-child {
        margin-bottom: 0;
    }

    .status-progress-row .progress {
        height: 6px;
        border-radius: 999px;
    }

    .compact-card .card-body {
        padding: 12px 14px;
    }

    .dashboard-bottom-grid>[class*="col-"] {
        display: flex;
    }

    .dashboard-bottom-grid .card {
        width: 100%;
    }

    .dashboard-card .badge {
        font-size: 11.5px;
        padding: 4px 7px;
    }

    .dashboard-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        margin-bottom: 16px;
        background: #fff;
        border: 1px solid #e9edf2;
        border-radius: 8px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    .dashboard-summary-item {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        min-height: 92px;
        padding: 16px 18px;
        color: inherit;
        text-decoration: none;
        border-right: 1px solid #eef1f4;
    }

    .dashboard-summary-item:last-child {
        border-right: 0;
    }

    .dashboard-summary-item:hover,
    .dashboard-summary-item:focus {
        color: inherit;
        text-decoration: none;
        background: #f8fafc;
    }

    .dashboard-summary-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 7px;
        color: var(--summary-color, #2563eb);
        background: var(--summary-background, #eff6ff);
    }

    .dashboard-summary-copy {
        min-width: 0;
    }

    .dashboard-summary-label,
    .dashboard-summary-meta {
        display: block;
        color: #6b7280;
        font-size: 11px;
        line-height: 1.25;
    }

    .dashboard-summary-label {
        font-weight: 700;
    }

    .dashboard-summary-value {
        display: block;
        overflow: hidden;
        color: #111827;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.25;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .revenue-chart-shell {
        border: 0;
        padding: 0;
    }

    .dashboard-main-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 20px;
        align-items: stretch;
        margin-bottom: 20px;
    }

    .dashboard-main-grid--revenue.dashboard-main-grid--booking {
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        grid-template-areas:
            "revenue status"
            "monthly bookings";
    }

    .dashboard-grid-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        height: 400px;
        margin-bottom: 0;
        overflow: hidden;
    }

    .dashboard-grid-card .card-header {
        flex: 0 0 auto;
    }

    .dashboard-grid-card .card-body {
        flex: 1 1 auto;
        min-height: 0;
    }

    .dashboard-revenue-card {
        grid-area: auto;
    }

    .dashboard-status-card {
        grid-area: auto;
    }

    .dashboard-monthly-card {
        grid-area: auto;
    }

    .dashboard-bookings-card {
        grid-area: auto;
    }

    .dashboard-main-grid--revenue.dashboard-main-grid--booking .dashboard-revenue-card {
        grid-area: revenue;
    }

    .dashboard-main-grid--revenue.dashboard-main-grid--booking .dashboard-status-card {
        grid-area: status;
    }

    .dashboard-main-grid--revenue.dashboard-main-grid--booking .dashboard-monthly-card {
        grid-area: monthly;
    }

    .dashboard-main-grid--revenue.dashboard-main-grid--booking .dashboard-bookings-card {
        grid-area: bookings;
    }

    .dashboard-bookings-card .card-body {
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .dashboard-chart-body,
    .revenue-chart-shell,
    #revenueGuestChart,
    #monthlyBookingRevenueChart,
    #container {
        height: 100%;
        min-height: 0;
    }
    .dashboard-follow-up {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }

    .dashboard-follow-up-section {
        min-width: 0;
        padding: 0 16px 4px;
    }

    .dashboard-follow-up-section + .dashboard-follow-up-section {
        border-left: 1px solid #eef1f4;
    }

    .dashboard-follow-up-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 12px 0 4px;
    }

    .dashboard-follow-up-heading h4 {
        margin: 0;
        color: #111827;
        font-size: 13px;
        font-weight: 800;
    }
    @media (max-width: 1199px) {
        .mini-stat-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .overview-grid,
        .quick-action-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .revenue-chart-summary {
            grid-template-columns: 1fr;
        }

        .dashboard-main-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-main-grid--revenue.dashboard-main-grid--booking {
            grid-template-areas:
                "revenue"
                "monthly"
                "status"
                "bookings";
        }

        .dashboard-grid-card {
            height: 380px;
        }
        .dashboard-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-summary-item:nth-child(2) {
            border-right: 0;
        }

        .dashboard-summary-item:nth-child(-n+2) {
            border-bottom: 1px solid #eef1f4;
        }
    }

    @media (max-width: 575px) {
        .dashboard-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-hero-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .dashboard-hero-actions .btn {
            flex: 1 1 140px;
        }

        .overview-grid,
        .mini-stat-grid,
        .quick-action-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-main-grid {
            gap: 14px;
        }

        .dashboard-grid-card {
            height: 350px;
        }

        .dashboard-summary,
        .dashboard-follow-up {
            grid-template-columns: 1fr;
        }

        .dashboard-summary-item,
        .dashboard-summary-item:nth-child(2) {
            border-right: 0;
            border-bottom: 1px solid #eef1f4;
        }

        .dashboard-summary-item:last-child {
            border-bottom: 0;
        }

        .dashboard-follow-up-section + .dashboard-follow-up-section {
            border-top: 1px solid #eef1f4;
            border-left: 0;
        }
        .chart-filter-form {
            width: 100%;
        }

        .chart-filter-form .form-control,
        .chart-filter-form .btn {
            width: 100%;
            margin-right: 0 !important;
        }
    }
</style>
@stop
@section('content')
@php
    $adminUser = Auth::guard('admins')->user();
    $isFullAdmin = $adminUser && $adminUser->can('full-quyen-quan-ly');
    $canPublishTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'duyet-xuat-ban-tour']);
    $canCreateTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
    $canViewTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
    $canOperateBooking = $adminUser && (
        $isFullAdmin
        || $adminUser->can('cap-nhat-trang-thai-dat-tour')
        || $adminUser->hasRoleName(['quan-ly-van-hanh', 'nhan-vien-booking'])
    );
    $canViewRevenue = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'xem-doanh-thu']);
    $canManageContent = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']);
    $canManageComments = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-binh-luan']);
    $canViewHotel = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-khach-san']);
    $kpiSectionLabel = $canOperateBooking ? 'KPI vận hành công ty du lịch' : 'KPI tài chính';
@endphp
<section class="content-header">
    <div class="container-fluid">
        <div class="dashboard-hero">
            <div>
                <h1 class="font-weight-bold">Bảng điều khiển</h1>
            </div>
            <div class="dashboard-hero-actions">
                @if($canCreateTour)
                <a href="{{ route('tour.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-plus"></i> Thêm tour
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="dashboard-summary">
            @if($canViewRevenue)
            <a href="{{ route('admin.revenue.month', ['select_month' => Request::get('select_month', date('m')), 'select_year' => Request::get('select_year', date('Y'))]) }}"
                class="dashboard-summary-item" style="--summary-color:#047857;--summary-background:#ecfdf5;">
                <span class="dashboard-summary-icon"><i class="fas fa-coins"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Doanh thu tháng</span>
                    <strong class="dashboard-summary-value">{{ number_format($totalRevenueMonth, 0, ',', '.') }} ₫</strong>
                    <small class="dashboard-summary-meta">{{ number_format($totalGuestsMonth) }} lượt khách</small>
                </span>
            </a>
            @endif
            @if($canOperateBooking)
            <a href="{{ route('book.tour.index', ['b_status' => 'pending']) }}" class="dashboard-summary-item"
                style="--summary-color:#b45309;--summary-background:#fffbeb;">
                <span class="dashboard-summary-icon"><i class="fas fa-clock"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Cần xử lý</span>
                    <strong class="dashboard-summary-value">{{ number_format($pendingBookings) }} đơn</strong>
                    <small class="dashboard-summary-meta">{{ number_format($newBookingsToday) }} đơn mới hôm nay</small>
                </span>
            </a>
            <a href="{{ route('book.tour.index') }}" class="dashboard-summary-item"
                style="--summary-color:#1d4ed8;--summary-background:#eff6ff;">
                <span class="dashboard-summary-icon"><i class="fas fa-receipt"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Tổng booking</span>
                    <strong class="dashboard-summary-value">{{ number_format($bookTour) }} đơn</strong>
                    <small class="dashboard-summary-meta">{{ $bookingCompletionRate }}% đã hoàn tất</small>
                </span>
            </a>
            @endif
            @if($canViewTour)
            <a href="{{ route('tour.index', ['t_status' => 1]) }}" class="dashboard-summary-item"
                style="--summary-color:#6d28d9;--summary-background:#f5f3ff;">
                <span class="dashboard-summary-icon"><i class="fas fa-route"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Tour đang bán</span>
                    <strong class="dashboard-summary-value">{{ number_format($publishedTours) }} tour</strong>
                    <small class="dashboard-summary-meta">{{ number_format($visibleHotels) }} khách sạn đang hiển thị</small>
                </span>
            </a>
            @endif
            @if($canViewHotel)
            <a href="{{ route('hotel.index') }}" class="dashboard-summary-item"
                style="--summary-color:#0f766e;--summary-background:#f0fdfa;">
                <span class="dashboard-summary-icon"><i class="fas fa-hotel"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Khách sạn</span>
                    <strong class="dashboard-summary-value">{{ number_format($hotel) }} khách sạn</strong>
                    <small class="dashboard-summary-meta">{{ number_format($visibleHotels) }} đang hiển thị</small>
                </span>
            </a>
            @endif
            @if($canPublishTour)
            <a href="{{ route('tour.index', ['t_status' => \App\Models\Tour::STATUS_PENDING_REVIEW]) }}" class="dashboard-summary-item"
                style="--summary-color:#0369a1;--summary-background:#f0f9ff;">
                <span class="dashboard-summary-icon"><i class="fas fa-user-check"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Tour chờ duyệt</span>
                    <strong class="dashboard-summary-value">{{ number_format($pendingReviewTours) }} tour</strong>
                    <small class="dashboard-summary-meta">Cần kiểm tra trước khi hiển thị</small>
                </span>
            </a>
            @endif
            @if($canManageContent)
            <a href="{{ route('article.index') }}" class="dashboard-summary-item"
                style="--summary-color:#7c2d12;--summary-background:#fff7ed;">
                <span class="dashboard-summary-icon"><i class="fas fa-newspaper"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Nội dung</span>
                    <strong class="dashboard-summary-value">{{ number_format($article) }} bài</strong>
                    <small class="dashboard-summary-meta">Bài viết, danh mục, địa điểm</small>
                </span>
            </a>
            @endif
            @if($canManageComments)
            <a href="{{ route('comment.index') }}" class="dashboard-summary-item"
                style="--summary-color:#4338ca;--summary-background:#eef2ff;">
                <span class="dashboard-summary-icon"><i class="fas fa-comments"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Bình luận</span>
                    <strong class="dashboard-summary-value">{{ number_format($comment) }} bình luận</strong>
                    <small class="dashboard-summary-meta">{{ number_format($hiddenComments) }} bình luận đang ẩn</small>
                </span>
            </a>
            @endif
            @if($canOperateBooking)
            <a href="{{ route('book.tour.index', ['b_status' => \App\Models\BookTour::STATUS_CANCELLED]) }}" class="dashboard-summary-item"
                style="--summary-color:#b91c1c;--summary-background:#fef2f2;">
                <span class="dashboard-summary-icon"><i class="fas fa-ban"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Tỷ lệ hủy</span>
                    <strong class="dashboard-summary-value">{{ $bookingCancellationRate }}%</strong>
                    <small class="dashboard-summary-meta">{{ number_format($cancelledBookings) }} đơn đã hủy</small>
                </span>
            </a>
            <a href="{{ route('admin.booking.overview', ['upcoming' => 1]) }}" class="dashboard-summary-item"
                style="--summary-color:#0f766e;--summary-background:#f0fdfa;">
                <span class="dashboard-summary-icon"><i class="fas fa-calendar-day"></i></span>
                <span class="dashboard-summary-copy">
                    <span class="dashboard-summary-label">Sắp khởi hành</span>
                    <strong class="dashboard-summary-value">{{ number_format($upcomingBookingCount) }} đơn</strong>
                    <small class="dashboard-summary-meta">Đã xác nhận/thanh toán trong 3 ngày tới</small>
                </span>
            </a>
            @endif
        </div>
        @if($canOperateBooking || $canViewRevenue)
        <div class="dashboard-section-label">{{ $kpiSectionLabel }}</div>
        <div class="mini-stat-grid">
            @if($canViewRevenue)
            <a href="{{ route('admin.revenue.month', ['select_month' => Request::get('select_month', date('m')), 'select_year' => Request::get('select_year', date('Y'))]) }}"
                class="mini-stat-tile" style="--tile-color:#047857;">
                <span class="mini-stat-icon"><i class="fas fa-wallet"></i></span>
                <span>
                    <span class="mini-stat-label">Giá trị TB/booking</span>
                    <strong class="mini-stat-number">{{ number_format($averageBookingValue, 0, ',', '.') }} ₫</strong>
                </span>
            </a>
            @endif
            @if($canOperateBooking)
            <a href="{{ route('book.tour.index', ['b_status' => \App\Models\BookTour::STATUS_CONFIRMED]) }}"
                class="mini-stat-tile" style="--tile-color:#2563eb;">
                <span class="mini-stat-icon"><i class="fas fa-check-circle"></i></span>
                <span>
                    <span class="mini-stat-label">Tỷ lệ xác nhận</span>
                    <strong class="mini-stat-number">{{ $bookingConfirmationRate }}%</strong>
                </span>
            </a>
            <a href="{{ route('book.tour.index', ['b_assigned_staff_id' => 'unassigned', 'b_status' => 'pending']) }}"
                class="mini-stat-tile" style="--tile-color:#b45309;">
                <span class="mini-stat-icon"><i class="fas fa-user-clock"></i></span>
                <span>
                    <span class="mini-stat-label">Chưa phân công</span>
                    <strong class="mini-stat-number">{{ number_format($unassignedActiveBookingCount) }} đơn</strong>
                </span>
            </a>
            <a href="{{ route('book.tour.index') }}"
                class="mini-stat-tile" style="--tile-color:#7c3aed;">
                <span class="mini-stat-icon"><i class="fas fa-redo-alt"></i></span>
                <span>
                    <span class="mini-stat-label">Khách quay lại</span>
                    <strong class="mini-stat-number">{{ $returningCustomerRate }}%</strong>
                </span>
            </a>
            @endif
            @if($canViewRevenue)
            <a href="{{ route('admin.revenue.month', ['select_month' => Request::get('select_month', date('m')), 'select_year' => Request::get('select_year', date('Y'))]) }}"
                class="mini-stat-tile" style="--tile-color:#dc2626;">
                <span class="mini-stat-icon"><i class="fas fa-chart-line"></i></span>
                <span>
                    <span class="mini-stat-label">Dự thu hiệu lực</span>
                    <strong class="mini-stat-number">{{ number_format($expectedRevenue, 0, ',', '.') }} ₫</strong>
                </span>
            </a>
            @endif
        </div>
        @endif
        <div class="dashboard-main-grid {{ $canViewRevenue ? 'dashboard-main-grid--revenue' : '' }} {{ $canOperateBooking ? 'dashboard-main-grid--booking' : '' }}">
            @if($canViewRevenue)
            <div class="card dashboard-card chart-card dashboard-grid-card dashboard-revenue-card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <div class="chart-title-group">
                        <h3 class="card-title font-weight-bold mb-0">Doanh thu & lượt khách</h3>
                    </div>
                    <form action="" method="GET" class="form-inline ml-auto admin-search-form chart-filter-form">
                        <?php $month = date('m'); $year = date('Y'); ?>
                        <select name="select_month" class="form-control form-control-sm mr-2">
                            <option value="">Tháng</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{$i}}" {{ (Request::get('select_month') ?? $month) == $i ? 'selected' : '' }}>Tháng {{$i}}</option>
                            @endfor
                        </select>
                        <select name="select_year" class="form-control form-control-sm mr-2">
                            <option value="">Năm</option>
                            @for($i = $year - 5; $i <= $year + 2; $i++)
                                <option value="{{$i}}" {{ (Request::get('select_year') ?? $year) == $i ? 'selected' : '' }}>Năm {{$i}}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm admin-search-btn">
                            <i class="fas fa-filter"></i> Lọc
                        </button>
                    </form>
                </div>
                <div class="card-body pt-0 pt-md-2 dashboard-chart-body">
                    <div class="revenue-chart-shell">
                        <div id="revenueGuestChart" data-list-day="{{ $listDay }}" data-money="{{ $arrmoney }}"
                            data-adults="{{ $arrRevenueTransactionMonth }}" data-children="{{ $arrRevenueTransactionMonthDefault }}"></div>
                    </div>
                </div>
            </div>
            @endif

            @if($canOperateBooking)
            <div class="card dashboard-card compact-card dashboard-grid-card dashboard-status-card">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bold">Trạng thái booking</h3>
                </div>
                <div class="card-body pt-0 dashboard-chart-body">
                    <div id="container" data-json="{{ $statusTransaction }}"></div>
                </div>
            </div>
            @endif

            @if($canViewRevenue)
            <div class="card dashboard-card dashboard-grid-card dashboard-monthly-card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-chart-bar text-success mr-1"></i> Doanh thu tháng
                    </h3>
                    <div class="text-right">
                        <div class="admin-list-meta mt-1">
                            Quý này: {{ number_format($totalRevenueQuarter, 0, ',', '.') }} ₫ · Năm nay: {{ number_format($totalRevenueYear, 0, ',', '.') }} ₫
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0 pt-md-2 dashboard-chart-body">
                    <div id="monthlyBookingRevenueChart" data-labels="{{ $monthlyLabels }}"
                        data-bookings="{{ $monthlyBookingCounts }}" data-revenue="{{ $monthlyRevenueTotals }}"></div>
                </div>
            </div>
            @endif

            @if($canOperateBooking)
            <div class="card dashboard-card dashboard-grid-card dashboard-bookings-card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-receipt text-primary mr-1"></i> Booking gần đây
                    </h3>
                    <a href="{{ route('book.tour.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list"></i> Tất cả
                    </a>
                </div>
                <div class="card-body pt-0">
                    @forelse($latestBookings as $booking)
                        @php
                            $bookingTotal = $booking->total_price;
                            $badgeClass = str_replace('btn-', 'badge-', \App\Models\BookTour::CLASS_STATUS[$booking->b_status] ?? 'badge-secondary');
                        @endphp
                        <div class="admin-list-item">
                            <div>
                                <div class="admin-list-title">#{{ $booking->id }} - {{ $booking->b_name }}</div>
                                <div class="admin-list-meta">
                                    {{ optional($booking->tour)->t_title ?: 'Tour không tồn tại' }}
                                    &nbsp;•&nbsp; {{ number_format($booking->total_guests) }} khách
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge {{ $badgeClass }}">{{ \App\Models\BookTour::STATUS[$booking->b_status] ?? 'Không rõ' }}</span>
                                <div class="admin-list-meta mt-1">{{ number_format($bookingTotal, 0, ',', '.') }} ₫</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có đơn đặt tour mới.</div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
        @if($canViewTour || $canOperateBooking || $canViewRevenue)
        <div class="card dashboard-card mt-1">
            <div class="dashboard-follow-up">
                @if($canViewTour)
                <section class="dashboard-follow-up-section">
                    <div class="dashboard-follow-up-heading">
                        <h4><i class="fas fa-fire text-danger mr-1"></i> Tour được đặt nhiều</h4>
                        <a href="{{ route('tour.index') }}" class="btn btn-sm btn-link">Xem tour</a>
                    </div>
                    @forelse($topBookedTours as $topBookedTour)
                        <div class="admin-list-item">
                            <div>
                                <div class="admin-list-title">{{ Str::limit(optional($topBookedTour->tour)->t_title ?: 'Tour không tồn tại', 52) }}</div>
                                <div class="admin-list-meta">
                                    {{ number_format($topBookedTour->guests_count) }} khách &nbsp;•&nbsp;
                                    {{ number_format($topBookedTour->revenue_total, 0, ',', '.') }} ₫
                                </div>
                            </div>
                            <span class="badge badge-success align-self-start">{{ number_format($topBookedTour->bookings_count) }} đơn</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có tour được đặt.</div>
                    @endforelse
                </section>
                @endif
                @if($canOperateBooking)
                <section class="dashboard-follow-up-section">
                    <div class="dashboard-follow-up-heading">
                        <h4><i class="fas fa-calendar-day text-info mr-1"></i> Booking sắp khởi hành</h4>
                        <a href="{{ route('admin.booking.overview', ['upcoming' => 1]) }}" class="btn btn-sm btn-link">Xem lịch</a>
                    </div>
                    @forelse($upcomingBookings as $upcomingBooking)
                        <div class="admin-list-item">
                            <div>
                                <div class="admin-list-title">{{ $upcomingBooking->display_code }} - {{ $upcomingBooking->b_name }}</div>
                                <div class="admin-list-meta">
                                    {{ optional($upcomingBooking->tour)->t_title ?: 'Tour không tồn tại' }}
                                    &nbsp;•&nbsp; {{ $upcomingBooking->b_start_date ? $upcomingBooking->b_start_date->format('d/m/Y') : '---' }}
                                </div>
                            </div>
                            <span class="badge badge-info align-self-start">{{ number_format($upcomingBooking->total_guests) }} khách</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có booking sắp khởi hành.</div>
                    @endforelse
                </section>
                @endif
                @if($canViewRevenue)
                <section class="dashboard-follow-up-section">
                    <div class="dashboard-follow-up-heading">
                        <h4><i class="fas fa-coins text-success mr-1"></i> Doanh thu theo tour</h4>
                        <a href="{{ route('admin.revenue.month', ['select_month' => Request::get('select_month', date('m')), 'select_year' => Request::get('select_year', date('Y'))]) }}" class="btn btn-sm btn-link">Xem doanh thu</a>
                    </div>
                    @forelse($topRevenueTours as $topRevenueTour)
                        <div class="admin-list-item">
                            <div>
                                <div class="admin-list-title">{{ Str::limit(optional($topRevenueTour->tour)->t_title ?: 'Tour không tồn tại', 52) }}</div>
                                <div class="admin-list-meta">
                                    {{ number_format($topRevenueTour->bookings_count) }} đơn &nbsp;•&nbsp;
                                    {{ number_format($topRevenueTour->guests_count) }} khách
                                </div>
                            </div>
                            <span class="badge badge-success align-self-start">{{ number_format($topRevenueTour->revenue_total, 0, ',', '.') }} ₫</span>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Chưa có doanh thu theo tour.</div>
                    @endforelse
                </section>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>
@stop

@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    // Data Parsing
    const dataTransaction = JSON.parse($("#container").attr('data-json') || '[]');
    const revenueGuestChart = $("#revenueGuestChart");
    const listday = JSON.parse(revenueGuestChart.attr("data-list-day") || '[]');
    const listMoneyMonth = JSON.parse(revenueGuestChart.attr('data-adults') || '[]');
    const listMoneyMonthDefault = JSON.parse(revenueGuestChart.attr('data-children') || '[]');
    const listMoneyMonth2 = JSON.parse(revenueGuestChart.attr('data-money') || '[]');
    const monthlyChart = $("#monthlyBookingRevenueChart");
    const monthlyLabels = JSON.parse(monthlyChart.attr('data-labels') || '[]');
    const monthlyBookingCounts = JSON.parse(monthlyChart.attr('data-bookings') || '[]');
    const monthlyRevenueTotals = JSON.parse(monthlyChart.attr('data-revenue') || '[]');
    const totalGuestsByDay = listday.map((day, index) => {
        return (Number(listMoneyMonth[index]) || 0) + (Number(listMoneyMonthDefault[index]) || 0);
    });
    const dayLabels = listday.map(day => {
        const parts = String(day).split('-');
        return parts.length === 3 ? parts[2] : day;
    });
    const activeRevenueDays = listMoneyMonth2.filter(value => Number(value) > 0).length;
    const formatMoneyShort = value => {
        const number = Number(value) || 0;
        if (Math.abs(number) >= 1000000000) {
            return Highcharts.numberFormat(number / 1000000000, 1, ',', '.') + ' tỷ';
        }
        if (Math.abs(number) >= 1000000) {
            return Highcharts.numberFormat(number / 1000000, 1, ',', '.') + ' triệu';
        }
        if (Math.abs(number) >= 1000) {
            return Highcharts.numberFormat(number / 1000, 0, ',', '.') + ' nghìn';
        }
        return Highcharts.numberFormat(number, 0, ',', '.');
    };
    $("#revenueActiveDays").text(activeRevenueDays + '/' + listday.length);

    // Common Theme
    Highcharts.setOptions({
        colors: ['#059669', '#2563eb', '#f59e0b', '#ef4444', '#6366f1', '#06b6d4'],
        lang: {
            thousandsSep: '.',
            decimalPoint: ','
        },
        chart: {
            style: {
                fontFamily: "'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
            }
        }
    });

    // 1. Pie Chart (Status)
    if ($("#container").length) {
    Highcharts.chart('container', {
        chart: { type: 'pie', backgroundColor: 'transparent' },
        title: { text: null },
        tooltip: { pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true, cursor: 'pointer',
                dataLabels: { enabled: false },
                showInLegend: true
            }
        },
        series: [{
            name: 'Tỷ lệ',
            colorByPoint: true,
            keys: ['name', 'y', 'selected', 'sliced'],
            data: dataTransaction
        }],
        credits: { enabled: false }
    });
    }

    // 2. Combo Chart (Revenue + Guests)
    if ($("#revenueGuestChart").length) {
    Highcharts.chart('revenueGuestChart', {
        chart: {
            backgroundColor: 'transparent',
            spacingTop: 12
        },
        title: { text: null },
        xAxis: {
            categories: dayLabels,
            crosshair: {
                color: 'rgba(37, 99, 235, .08)'
            },
            lineColor: '#e5e7eb',
            tickColor: '#e5e7eb',
            labels: {
                style: { color: '#6b7280', fontSize: '11px' }
            }
        },
        yAxis: [{
            title: { text: 'Doanh thu đã thanh toán', style: { color: '#059669', fontWeight: '700' } },
            labels: {
                style: { color: '#059669' },
                formatter: function () { return formatMoneyShort(this.value); }
            },
            gridLineColor: '#eef2f7'
        }, {
            title: { text: 'Lượt khách', style: { color: '#2563eb', fontWeight: '700' } },
            labels: {
                style: { color: '#2563eb' },
                formatter: function () { return Highcharts.numberFormat(this.value, 0, ',', '.'); }
            },
            min: 0,
            opposite: true,
            gridLineWidth: 0
        }],
        legend: {
            align: 'left',
            verticalAlign: 'top',
            symbolRadius: 6,
            itemStyle: { color: '#374151', fontWeight: '700' }
        },
        tooltip: {
            shared: true,
            useHTML: true,
            borderWidth: 1,
            borderColor: '#e5e7eb',
            borderRadius: 10,
            backgroundColor: '#ffffff',
            shadow: {
                color: 'rgba(15, 23, 42, .16)',
                offsetX: 0,
                offsetY: 8,
                opacity: .18,
                width: 14
            },
            style: { color: '#111827' },
            formatter: function () {
                const dateLabel = listday[this.points[0].point.index] || this.x;
                let html = '<div style="min-width:180px;color:#111827;"><strong style="color:#111827;">Ngày ' + dateLabel + '</strong>';
                this.points.forEach(point => {
                    const value = point.series.userOptions.isMoney
                        ? Highcharts.numberFormat(point.y, 0, ',', '.') + ' ₫'
                        : Highcharts.numberFormat(point.y, 0, ',', '.') + ' khách';
                    html += '<div style="margin-top:6px;color:#374151;"><span style="color:' + point.color + '">●</span> ' + point.series.name + ': <b style="color:#111827;">' + value + '</b></div>';
                });
                return html + '</div>';
            }
        },
        plotOptions: {
            column: {
                borderWidth: 0,
                borderRadius: 5,
                pointPadding: 0.08,
                groupPadding: 0.08
            },
            spline: {
                lineWidth: 3,
                marker: {
                    enabled: false,
                    radius: 4,
                    symbol: 'circle'
                }
            },
            areaspline: {
                lineWidth: 2,
                fillOpacity: .12,
                marker: { enabled: false }
            },
            series: {
                states: {
                    inactive: { opacity: .35 }
                }
            }
        },
        series: [{
            type: 'column',
            name: 'Doanh thu đã thanh toán',
            data: listMoneyMonth2,
            color: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [[0, '#34d399'], [1, '#059669']]
            },
            isMoney: true,
            tooltip: { valueSuffix: ' ₫' }
        }, {
            type: 'spline',
            name: 'Tổng khách',
            data: totalGuestsByDay,
            color: '#2563eb',
            yAxis: 1
        }, {
            type: 'areaspline',
            name: 'Người lớn',
            data: listMoneyMonth,
            color: '#7c3aed',
            yAxis: 1,
            visible: false
        }, {
            type: 'areaspline',
            name: 'Trẻ em',
            data: listMoneyMonthDefault,
            color: '#f59e0b',
            yAxis: 1,
            visible: false
        }],
        responsive: {
            rules: [{
                condition: { maxWidth: 520 },
                chartOptions: {
                    xAxis: { labels: { step: 2 } },
                    legend: { itemStyle: { fontSize: '11px' } }
                }
            }]
        },
        credits: { enabled: false }
    });
    }

    // 3. Monthly Booking + Revenue Chart
    if ($("#monthlyBookingRevenueChart").length) {
    Highcharts.chart('monthlyBookingRevenueChart', {
        chart: {
            backgroundColor: 'transparent',
            spacingTop: 12
        },
        title: { text: null },
        xAxis: {
            categories: monthlyLabels,
            crosshair: { color: 'rgba(5, 150, 105, .08)' },
            lineColor: '#e5e7eb',
            tickColor: '#e5e7eb',
            labels: { style: { color: '#6b7280', fontSize: '11px' } }
        },
        yAxis: [{
            title: { text: 'Doanh thu tháng', style: { color: '#059669', fontWeight: '700' } },
            labels: {
                style: { color: '#059669' },
                formatter: function () { return formatMoneyShort(this.value); }
            },
            gridLineColor: '#eef2f7'
        }, {
            title: { text: 'Booking', style: { color: '#2563eb', fontWeight: '700' } },
            labels: {
                style: { color: '#2563eb' },
                formatter: function () { return Highcharts.numberFormat(this.value, 0, ',', '.'); }
            },
            min: 0,
            opposite: true,
            gridLineWidth: 0
        }],
        legend: {
            align: 'left',
            verticalAlign: 'top',
            symbolRadius: 6,
            itemStyle: { color: '#374151', fontWeight: '700' }
        },
        tooltip: {
            shared: true,
            useHTML: true,
            borderWidth: 1,
            borderColor: '#e5e7eb',
            borderRadius: 10,
            backgroundColor: '#ffffff',
            formatter: function () {
                let html = '<div style="min-width:180px;color:#111827;">';
                this.points.forEach(point => {
                    const value = point.series.userOptions.isMoney
                        ? Highcharts.numberFormat(point.y, 0, ',', '.') + ' ₫'
                        : Highcharts.numberFormat(point.y, 0, ',', '.') + ' booking';
                    html += '<div style="margin-top:6px;color:#374151;"><span style="color:' + point.color + '">●</span> ' + point.series.name + ': <b>' + value + '</b></div>';
                });
                return html + '</div>';
            }
        },
        plotOptions: {
            column: {
                borderWidth: 0,
                borderRadius: 5,
                pointPadding: 0.08,
                groupPadding: 0.08
            },
            spline: {
                lineWidth: 3,
                marker: { enabled: false, radius: 4, symbol: 'circle' }
            },
            series: {
                states: { inactive: { opacity: .35 } }
            }
        },
        series: [{
            type: 'column',
            name: 'Doanh thu tháng',
            data: monthlyRevenueTotals,
            color: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [[0, '#34d399'], [1, '#059669']]
            },
            isMoney: true
        }, {
            type: 'spline',
            name: 'Booking trong tháng',
             data: monthlyBookingCounts,
            color: '#2563eb',
            yAxis: 1
        }],
        credits: { enabled: false }
    });
    }
</script>
@stop
