@extends('admin.layouts.main')
@section('title', 'Doanh thu theo tour')
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
        .revenue-mini-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }
        .revenue-mini-card {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .revenue-mini-card span {
            color: #6b7280;
            display: block;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .revenue-mini-card strong {
            color: #111827;
            display: block;
            font-size: 24px;
            line-height: 1.2;
            margin-top: 4px;
        }
        .tour-rank-badge {
            align-items: center;
            background: #eef2ff;
            border-radius: 999px;
            color: #2563eb;
            display: inline-flex;
            font-weight: 800;
            height: 30px;
            justify-content: center;
            min-width: 30px;
            padding: 0 9px;
        }
        @media (max-width: 767px) {
            .revenue-mini-grid {
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
                    <h1 class="font-weight-bold">Doanh thu theo tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Doanh thu theo tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card revenue-summary-card shadow-sm mb-4">
                <div class="card-body">
                    <div class="revenue-label">Tổng doanh thu theo tour tháng {{ sprintf('%02d', $month) }}/{{ $year }}</div>
                    <div class="revenue-number">{{ number_format($totalRevenueMonth, 0, ',', '.') }} ₫</div>
                    <small>{{ number_format($totalPaidBookings) }} đơn đã thanh toán/hoàn tất trong tháng</small>
                    <i class="fas fa-coins revenue-icon"></i>
                </div>
            </div>
            <div class="revenue-mini-grid">
                <div class="revenue-mini-card">
                    <span>Tour có doanh thu</span>
                    <strong>{{ number_format($totalRevenueTours) }} tour</strong>
                </div>
                <div class="revenue-mini-card">
                    <span>Tổng booking</span>
                    <strong>{{ number_format($totalPaidBookings) }} đơn</strong>
                </div>
                <div class="revenue-mini-card">
                    <span>Tổng khách</span>
                    <strong>{{ number_format($totalGuestsMonth) }} khách</strong>
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
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Sắp xếp</label>
                                <select name="sort" class="form-control custom-select">
                                    <option value="revenue_desc" {{ $sort === 'revenue_desc' ? 'selected' : '' }}>Doanh thu cao nhất</option>
                                    <option value="revenue_asc" {{ $sort === 'revenue_asc' ? 'selected' : '' }}>Doanh thu thấp nhất</option>
                                    <option value="bookings_desc" {{ $sort === 'bookings_desc' ? 'selected' : '' }}>Nhiều booking nhất</option>
                                    <option value="guests_desc" {{ $sort === 'guests_desc' ? 'selected' : '' }}>Nhiều khách nhất</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 text-right admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('admin.home') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-arrow-left mr-1"></i> Về dashboard</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Xếp hạng doanh thu theo tour</h3>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover revenue-table m-0">
                        <thead>
                            <tr>
                                <th width="6%" class="text-center">Hạng</th>
                                <th width="30%">Tour</th>
                                <th width="14%" class="text-right">Doanh thu</th>
                                <th width="12%" class="text-center">Booking</th>
                                <th width="12%" class="text-center">Khách</th>
                                <th width="14%" class="text-right">TB/booking</th>
                                <th width="8%">Gần nhất</th>
                                <th width="4%" class="text-center">Đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$tourRevenueReports->isEmpty())
                                @php $i = $tourRevenueReports->firstItem(); @endphp
                                @foreach($tourRevenueReports as $report)
                                    @php
                                        $averageRevenue = $report->bookings_count > 0 ? round($report->revenue_total / $report->bookings_count) : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center"><span class="tour-rank-badge">{{ $i }}</span></td>
                                        <td>
                                            <div class="font-weight-bold text-primary">{{ optional($report->tour)->t_title ?? 'Tour không tồn tại' }}</div>
                                            <small class="text-muted">{{ optional($report->tour)->t_journeys ?: 'Chưa có hành trình' }}</small>
                                        </td>
                                        <td class="text-right revenue-money">{{ number_format($report->revenue_total, 0, ',', '.') }} ₫</td>
                                        <td class="text-center font-weight-bold">{{ number_format($report->bookings_count) }}</td>
                                        <td class="text-center">{{ number_format($report->guests_count) }}</td>
                                        <td class="text-right">{{ number_format($averageRevenue, 0, ',', '.') }} ₫</td>
                                        <td>{{ $report->latest_paid_at ? date('d/m/Y', strtotime($report->latest_paid_at)) : '---' }}</td>
                                        <td>
                                            <a href="{{ route('book.tour.index', ['b_tour_id' => $report->b_tour_id, 'b_status' => [\App\Models\BookTour::STATUS_PAID, \App\Models\BookTour::STATUS_COMPLETED]]) }}"
                                                class="btn btn-sm btn-outline-primary" title="Xem các đơn đã tạo doanh thu">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-coins fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có tour phát sinh doanh thu trong tháng này.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($tourRevenueReports->hasPages())
                    <div class="card-footer bg-white">
                        <div class="pagination-wrapper">
                            {{ $tourRevenueReports->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
