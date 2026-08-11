@extends('admin.layouts.main')
@section('title', 'Lịch đặt tour')
@section('style-css')
    <style>
        .booking-summary-card {
            border: 0;
            border-radius: 12px;
            background: #123f55;
            color: #fff;
            overflow: hidden;
        }
        .booking-summary-card .card-body {
            position: relative;
            z-index: 1;
        }
        .booking-summary-card .booking-label {
            color: rgba(255, 255, 255, .78);
            font-size: 13px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .booking-summary-card .booking-number {
            font-size: 34px;
            font-weight: 800;
            line-height: 1.15;
        }
        .booking-summary-card .booking-icon {
            position: absolute;
            right: 22px;
            bottom: 12px;
            color: rgba(255, 255, 255, .14);
            font-size: 74px;
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
                    <div class="booking-label">Tổng lượt đặt tour</div>
                    <div class="booking-number">{{ number_format($bookingCount) }}</div>
                    <small>{{ number_format($filteredBookingCount) }} đơn đang hiển thị - {{ number_format($filteredGuestCount) }} khách</small>
                    <i class="fas fa-shopping-cart booking-icon"></i>
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

            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách lịch đặt tour</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover booking-overview-table m-0">
                        <thead>
                            <tr>
                                <th width="6%" class="text-center">STT</th>
                                <th width="10%">Mã đơn</th>
                                <th width="24%">Tour</th>
                                <th width="20%">Khách hàng</th>
                                <th width="12%">Ngày đặt</th>
                                <th width="12%">Ngày đi mong muốn</th>
                                <th width="8%" class="text-center">Khách</th>
                                <th width="10%" class="text-center">Trạng thái</th>
                                <th width="6%" class="text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$bookings->isEmpty())
                                @php $i = $bookings->firstItem(); @endphp
                                @foreach($bookings as $booking)
                                    @php
                                        $guestCount = (int) $booking->b_number_adults
                                            + (int) $booking->b_number_children
                                            + (int) $booking->b_number_child6
                                            + (int) $booking->b_number_child2;
                                        $departureDate = $booking->b_start_date;
                                        $endDate = $booking->b_end_date;
                                        $badgeClass = str_replace('btn-', 'badge-', $classStatus[$booking->b_status] ?? 'badge-secondary');
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted">{{ $i }}</td>
                                        <td><span class="badge badge-light border">#{{ $booking->id }}</span></td>
                                        <td>
                                            <div class="booking-tour-name">{{ optional($booking->tour)->t_title ?? '---' }}</div>
                                            <small class="text-muted">{{ optional($booking->tour)->t_journeys ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $booking->b_name }}</div>
                                            <small class="text-muted">{{ $booking->b_phone }}{{ $booking->b_email ? ' - ' . $booking->b_email : '' }}</small>
                                        </td>
                                        <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y') : '---' }}</td>
                                        <td>
                                            {{ $departureDate ? date('d/m/Y', strtotime($departureDate)) : '---' }}
                                            @if($endDate)
                                                <small class="d-block text-muted">Về: {{ date('d/m/Y', strtotime($endDate)) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center booking-guest-count">{{ number_format($guestCount) }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeClass }}">{{ $status[$booking->b_status] ?? 'Không rõ' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('book.tour.index', ['booking_id' => $booking->id]) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết đơn">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có đơn đặt tour nào phù hợp.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                    <div class="card-footer bg-white">
                        <div class="pagination-wrapper">
                            {{ $bookings->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
