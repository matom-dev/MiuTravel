@extends('admin.layouts.main')
@section('title', 'Doanh thu đã thanh toán theo tháng')
@section('style-css')
    <style>
        .revenue-summary-card {
            border: 0;
            border-radius: 12px;
            background: #fff;
            color: #111827;
            overflow: hidden;
        }
        .revenue-summary-card .card-body {
            position: relative;
            z-index: 1;
        }
        .revenue-summary-card .revenue-label {
            color: #111827;
            font-size: 13px;
            letter-spacing: .04em;
            text-transform: uppercase;
            font-weight: 800;
        }
        .revenue-summary-card .revenue-number {
            color: #111827;
            font-size: 34px;
            font-weight: 800;
            line-height: 1.15;
        }
        .revenue-summary-card small {
            color: #111827;
        }
        .revenue-summary-card .revenue-icon {
            position: absolute;
            right: 22px;
            bottom: 12px;
            color: rgba(17, 24, 39, .08);
            font-size: 74px;
        }
        .revenue-table th {
            background: #f8fafc;
            color: #6b7280;
            font-size: 12px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .revenue-table td {
            vertical-align: middle;
        }
        .revenue-money {
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
                    <h1 class="font-weight-bold">Doanh thu đã thanh toán theo tháng</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Doanh thu đã thanh toán theo tháng</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card revenue-summary-card shadow-sm mb-4">
                <div class="card-body">
                    <div class="revenue-label">Tổng doanh thu đã thanh toán tháng {{ sprintf('%02d', $month) }}/{{ $year }}</div>
                    <div class="revenue-number">{{ number_format($totalRevenueMonth, 0, ',', '.') }} ₫</div>
                    <small>{{ number_format($revenueBookings->total()) }} đơn đã thanh toán trong tháng</small>
                    <i class="fas fa-coins revenue-icon"></i>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 bg-white pb-0">
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-filter mr-1"></i> Lọc doanh thu</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.revenue.month') }}" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tháng</label>
                                <select name="select_month" class="form-control custom-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ (int) $month === $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Năm</label>
                                <select name="select_year" class="form-control custom-select">
                                    @for($i = date('Y') - 5; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ (int) $year === $i ? 'selected' : '' }}>Năm {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 text-right admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('admin.home') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-arrow-left mr-1"></i> Về dashboard</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Đơn đã thanh toán trong tháng</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover revenue-table m-0">
                        <thead>
                            <tr>
                                <th width="6%" class="text-center">STT</th>
                                <th width="12%">Mã đơn</th>
                                <th width="26%">Tour</th>
                                <th width="22%">Khách hàng</th>
                                <th width="14%">Ngày thanh toán</th>
                                <th width="14%" class="text-right">Số tiền</th>
                                <th width="6%" class="text-center">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$revenueBookings->isEmpty())
                                @php $i = $revenueBookings->firstItem(); @endphp
                                @foreach($revenueBookings as $booking)
                                    <tr>
                                        <td class="text-center text-muted">{{ $i }}</td>
                                        <td><span class="badge badge-light border">#{{ $booking->id }}</span></td>
                                        <td>
                                            <div class="font-weight-bold text-primary">{{ optional($booking->tour)->t_title ?? '---' }}</div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $booking->b_name }}</div>
                                            <small class="text-muted">{{ $booking->b_phone }}{{ $booking->b_email ? ' - ' . $booking->b_email : '' }}</small>
                                        </td>
                                        <td>{{ $booking->created_at ? $booking->created_at->format('d/m/Y') : '---' }}</td>
                                        <td class="text-right revenue-money">{{ number_format($booking->revenue_total, 0, ',', '.') }} ₫</td>
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-coins fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có đơn đã thanh toán trong tháng này.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($revenueBookings->hasPages())
                    <div class="card-footer bg-white">
                        <div class="pagination-wrapper">
                            {{ $revenueBookings->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
