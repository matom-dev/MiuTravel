@extends('admin.layouts.main')
@section('title', 'Lịch đặt tour')
@section('style-css')
    <style>
        .booking-summary-card {
            border: 1px solid #eef2f7;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 8px 26px rgba(15, 23, 42, .06);
        }
        .booking-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .booking-summary-item {
            align-items: center;
            background: #f8fafc;
            border: 1px solid #edf1f5;
            border-left: 4px solid var(--summary-color, #2563eb);
            border-radius: 10px;
            display: flex;
            gap: 12px;
            min-height: 92px;
            padding: 14px 16px;
        }
        .booking-summary-icon {
            align-items: center;
            background: rgba(37, 99, 235, .08);
            border-radius: 10px;
            color: var(--summary-color, #2563eb);
            display: inline-flex;
            flex: 0 0 auto;
            font-size: 18px;
            height: 44px;
            justify-content: center;
            width: 44px;
        }
        .booking-summary-card .booking-label {
            color: #6b7280;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .04em;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .booking-summary-card .booking-number {
            color: #111827;
            font-size: 28px;
            font-weight: 800;
            line-height: 1.15;
            margin-top: 3px;
        }
        .booking-summary-card small {
            color: #6b7280;
            display: block;
            font-size: 12px;
            margin-top: 3px;
        }
        .booking-overview-table th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .booking-overview-table td {
            vertical-align: middle;
        }
        .booking-tour-name {
            color: #2563eb;
            font-weight: 700;
        }
        .booking-guest-count {
            color: #f15b2a;
            font-weight: 800;
            white-space: nowrap;
        }
        @media (max-width: 991.98px) {
            .booking-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Lịch đặt tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Lịch đặt tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card booking-summary-card shadow-sm mb-4">
                <div class="card-body">
                    <div class="booking-summary-grid">
                        <div class="booking-summary-item" style="--summary-color:#2563eb;">
                            <span class="booking-summary-icon"><i class="fas fa-shopping-cart"></i></span>
                            <div>
                                <div class="booking-label">Tổng lượt đặt tour</div>
                                <div class="booking-number">{{ number_format($bookingCount) }}</div>
                                <small>Tất cả đơn trong hệ thống</small>
                            </div>
                        </div>
                        <div class="booking-summary-item" style="--summary-color:#f15b2a;">
                            <span class="booking-summary-icon"><i class="fas fa-filter"></i></span>
                            <div>
                                <div class="booking-label">Đơn đang hiển thị</div>
                                <div class="booking-number">{{ number_format($filteredBookingCount) }}</div>
                                <small>Theo bộ lọc hiện tại</small>
                            </div>
                        </div>
                        <div class="booking-summary-item" style="--summary-color:#059669;">
                            <span class="booking-summary-icon"><i class="fas fa-users"></i></span>
                            <div>
                                <div class="booking-label">Tổng khách</div>
                                <div class="booking-number">{{ number_format($filteredGuestCount) }}</div>
                                <small>Trong danh sách đang hiển thị</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 bg-white pb-0">
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-filter mr-1"></i> Lọc lịch đặt tour</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.booking.overview') }}" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Ngày khởi hành mong muốn</label>
                                <input type="date" name="b_start_date" value="{{ $selectedStartDate }}" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tháng khởi hành</label>
                                <select name="select_month" class="form-control custom-select">
                                    <option value="">Tất cả tháng</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (string) $selectedMonth === (string) $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Năm khởi hành</label>
                                <select name="select_year" class="form-control custom-select">
                                    <option value="">Tất cả năm</option>
                                    @for($i = date('Y') - 5; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ (string) $selectedYear === (string) $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                                <select name="b_status" class="form-control custom-select">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach($status as $key => $item)
                                        <option value="{{ $key }}" {{ (string) Request::get('b_status') === (string) $key ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-right admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('admin.booking.overview') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @include('admin.home._booking_list_table', ['listTitle' => 'Danh sách lịch đặt tour'])
        </div>
    </section>
@stop
