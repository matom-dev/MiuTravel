@extends('admin.agency.layout')
@section('agency-heading', 'Thống kê & tài chính')
@section('agency-description', 'Theo dõi doanh thu và kết quả kinh doanh theo thời gian, tour hoặc đợt khởi hành.')
@section('agency-body')
<section class="agency-panel" id="reports">
        <div class="agency-panel__head"><h2><i class="fas fa-chart-line text-info mr-2"></i>Thống kê & tài chính</h2><span class="agency-muted">Chỉ gồm dữ liệu của {{ $agency->name }}</span></div>
        <div class="agency-panel__body">
            <form method="get" action="{{ route('agency.dashboard') }}" class="mb-4"><div class="agency-fields">
                <label>Từ ngày<input class="form-control" type="date" name="from" value="{{ request('from') }}"></label><label>Đến ngày<input class="form-control" type="date" name="to" value="{{ request('to') }}"></label>
                <label>Tháng<select class="form-control" name="month"><option value="">Tất cả tháng</option>@for($month=1;$month<=12;$month++)<option value="{{ $month }}" @selected((string)request('month')===(string)$month)>Tháng {{ $month }}</option>@endfor</select></label><label>Năm<input class="form-control" type="number" min="2000" max="2100" name="year" placeholder="Ví dụ: 2026" value="{{ request('year') }}"></label>
                <label>Tour<select class="form-control" name="tour_id"><option value="">Tất cả tour</option>@foreach($tours as $tour)<option value="{{ $tour->id }}" @selected((string)request('tour_id')===(string)$tour->id)>{{ $tour->t_title }}</option>@endforeach</select></label>
                <label>Đợt khởi hành<select class="form-control" name="schedule_id"><option value="">Tất cả đợt</option>@foreach($tours as $tour)@foreach($tour->schedules as $schedule)<option value="{{ $schedule->id }}" @selected((string)request('schedule_id')===(string)$schedule->id)>{{ $tour->t_title }} · {{ substr($schedule->ts_start_date,0,10) }}</option>@endforeach @endforeach</select></label>
            </div><button class="btn btn-primary mt-3"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button><a class="btn btn-link mt-3" href="{{ route('agency.dashboard') }}">Xóa bộ lọc</a></form>
            <div class="agency-metrics">@foreach($metrics as $label=>$value)@php $money=in_array($label,['Giá trị đặt tour','Tiền đã thu','Tiền đã hoàn','Doanh thu ghi nhận','Phí nền tảng','Đại lý được hưởng','Thực thu ròng']); @endphp<div class="agency-metric {{ $money?'money':'' }}"><span>{{ $label }}</span><strong>{{ number_format($value,0,',','.') }}</strong>@if($money)<small>VND</small>@endif</div>@endforeach</div>
            <p class="agency-muted mt-3 mb-0">Lượt đăng ký theo ngày tạo; thu/hoàn theo ngày giao dịch; doanh thu theo ngày hoàn tất. VNPay đã thanh toán được cộng tự động.</p>
        </div>
    </section>
@endsection
