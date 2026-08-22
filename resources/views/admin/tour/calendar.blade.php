@extends('admin.layouts.main')
@section('title', 'Lịch tour')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Lịch khởi hành tour</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('tour.index') }}">Tour</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="admin-search-form">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size:13px;">Tour</label>
                            <select name="tour_id" class="form-control custom-select">
                                <option value="">Tất cả tour</option>
                                @foreach($tours as $tourOption)
                                    <option value="{{ $tourOption->id }}" {{ (string) $tourId === (string) $tourOption->id ? 'selected' : '' }}>
                                        {{ $tourOption->t_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size:13px;">Từ ngày</label>
                            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size:13px;">Đến ngày</label>
                            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                        </div>
                        <div class="col-md-2 admin-search-actions">
                            <button class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc</button>
                            <a href="{{ route('tour.calendar') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-alt text-primary mr-1"></i> Lịch đã chốt</h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover m-0">
                            <thead>
                                <tr>
                                    <th>Ngày đi</th>
                                    <th>Tour</th>
                                    <th class="text-center">Loại lịch</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($schedule->ts_start_date)->format('d/m/Y') }}</strong>
                                            <div class="small text-muted">Về: {{ \Carbon\Carbon::parse($schedule->ts_end_date)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>{{ optional($schedule->tour)->t_title ?: 'Tour không tồn tại' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-light border">Lịch tham khảo</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ (int) $schedule->ts_status === 1 ? 'badge-success' : 'badge-secondary' }}">
                                                {{ \App\Models\TourSchedule::STATUS[$schedule->ts_status] ?? 'Không rõ' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">Chưa có lịch khởi hành đã chốt trong khoảng này.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-user-clock text-warning mr-1"></i> Ngày khách mong muốn</h3>
                    </div>
                    <div class="card-body">
                        @forelse($requestedBookings as $booking)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>
                                    <strong>{{ $booking->display_code }} - {{ $booking->b_name }}</strong>
                                    <div class="small text-muted">{{ optional($booking->tour)->t_title ?: 'Tour không tồn tại' }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-light border">{{ $booking->b_start_date ? $booking->b_start_date->format('d/m/Y') : '---' }}</span>
                                    <div class="small text-muted">{{ number_format($booking->total_guests) }} khách</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">Không có booking mong muốn khởi hành trong khoảng này.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
