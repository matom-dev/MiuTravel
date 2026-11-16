@extends('admin.agency.layout')
@section('agency-heading', 'Thống kê & tài chính')
@section('agency-description', 'Theo dõi doanh thu và kết quả kinh doanh theo thời gian, tour hoặc đợt khởi hành.')
@section('agency-body')
<section class="agency-panel" id="reports">
        <div class="agency-panel__head"><h2><i class="fas fa-chart-line text-info mr-2"></i>Thống kê & tài chính</h2><span class="agency-muted">Chỉ gồm dữ liệu của {{ $agency->name }}</span></div>
        <div class="agency-panel__body">
            <form method="get" action="{{ route('agency.dashboard') }}" class="mb-4"><div class="agency-fields">
                <label>Nội dung biểu đồ<select class="form-control" name="chart_type"><option value="collected" @selected($revenueChart['type']==='collected')>Tiền đã thu</option><option value="revenue" @selected($revenueChart['type']==='revenue')>Doanh thu ghi nhận</option></select></label>
                <label>Từ ngày<input class="form-control" type="date" name="from" value="{{ request('from') }}"></label><label>Đến ngày<input class="form-control" type="date" name="to" value="{{ request('to') }}"></label>
                <label>Tháng<select class="form-control" name="month"><option value="">Tất cả tháng</option>@for($month=1;$month<=12;$month++)<option value="{{ $month }}" @selected((string)request('month')===(string)$month)>Tháng {{ $month }}</option>@endfor</select></label><label>Năm<input class="form-control" type="number" min="2000" max="2100" name="year" placeholder="Ví dụ: 2026" value="{{ request('year') }}"></label>
                <label>Tour<select class="form-control" name="tour_id"><option value="">Tất cả tour</option>@foreach($tours as $tour)<option value="{{ $tour->id }}" @selected((string)request('tour_id')===(string)$tour->id)>{{ $tour->t_title }}</option>@endforeach</select></label>
                <label>Đợt khởi hành<select class="form-control" name="schedule_id"><option value="">Tất cả đợt</option>@foreach($tours as $tour)@foreach($tour->schedules as $schedule)<option value="{{ $schedule->id }}" @selected((string)request('schedule_id')===(string)$schedule->id)>{{ $tour->t_title }} · {{ substr($schedule->ts_start_date,0,10) }}</option>@endforeach @endforeach</select></label>
            </div><button class="btn btn-primary mt-3"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button><a class="btn btn-link mt-3" href="{{ route('agency.dashboard') }}">Xóa bộ lọc</a></form>
            @if($revenueChart['type']==='collected')
                <p class="agency-muted">Biểu đồ Tiền đã thu chưa khấu trừ phí. Chọn <strong>Doanh thu ghi nhận</strong> trong Nội dung biểu đồ để xem phí nền tảng và số tiền đại lý được hưởng của các đơn hoàn tất.</p>
            @endif
            <section aria-labelledby="agency-revenue-heading" class="mb-4">
                <h3 id="agency-revenue-heading" class="h5">{{ $revenueChart['type']==='collected' ? 'Tiền đã thu' : 'Doanh thu ghi nhận' }} theo tháng · Năm {{ $revenueChart['year'] }}</h3>
                <p class="agency-muted">@if($revenueChart['type']==='collected') Tiền đã thu gồm khoản thu đã ghi nhận và VNPay thanh toán thành công, theo ngày giao dịch; chưa trừ tiền hoàn và không đồng nghĩa doanh thu. @else Chỉ tính đơn đã hoàn tất, theo ngày ghi nhận doanh thu. Tiền khách đã thanh toán cho chuyến chưa hoàn tất không được tính. @endif Biểu đồ áp dụng các bộ lọc phía trên; khi chưa chọn năm, mặc định năm hiện tại.</p>
                <div class="agency-metrics mb-3">
                    @foreach($chartColumns as $key=>$label)
                        <div class="agency-metric money"><span>{{ $label }} · {{ $revenueChart['year'] }}</span><strong>{{ number_format(array_sum($revenueChart[$key]),0,',','.') }}</strong><small>VND</small></div>
                    @endforeach
                </div>
                @if($chartHasData)
                    <div style="position:relative;height:320px"><canvas id="agency-revenue-chart" role="img" aria-label="{{ reset($chartColumns) }} theo 12 tháng năm {{ $revenueChart['year'] }}">Xem bảng số liệu theo tháng bên dưới.</canvas></div>
                @else
                    <div class="alert alert-info" role="status">{{ $revenueChart['type']==='collected' ? 'Chưa có khoản thu hoặc hoàn tiền trong năm và bộ lọc đã chọn.' : 'Chưa có doanh thu ghi nhận trong năm và bộ lọc đã chọn. Chỉ đơn đã hoàn tất và ghi nhận doanh thu mới được tính; bạn có thể chuyển sang Tiền đã thu để xem các khoản thanh toán.' }}</div>
                @endif
                <details class="mt-3"><summary>Xem bảng số liệu theo tháng</summary>
                    <div class="agency-scroll"><table class="agency-table"><thead><tr><th>Tháng</th>@foreach($chartColumns as $label)<th>{{ $label }} (VND)</th>@endforeach</tr></thead><tbody>
                    @foreach($revenueChart['labels'] as $index=>$label)
                        <tr><td>{{ $label }}</td>@foreach($chartColumns as $key=>$columnLabel)<td>{{ number_format($revenueChart[$key][$index],0,',','.') }}</td>@endforeach</tr>
                    @endforeach
                    </tbody></table></div>
                </details>
            </section>
            <div class="agency-metrics">@foreach($metrics as $label=>$value)@php $money=in_array($label,['Giá trị đặt tour','Tiền đã thu','Tiền đã hoàn','Doanh thu ghi nhận','Phí nền tảng','Đại lý được hưởng','Thực thu ròng']); @endphp<div class="agency-metric {{ $money?'money':'' }}"><span>{{ $label }}</span><strong>{{ number_format($value,0,',','.') }}</strong>@if($money)<small>VND</small>@endif</div>@endforeach</div>
            <p class="agency-muted mt-3 mb-0">Lượt đăng ký theo ngày tạo; thu/hoàn theo ngày giao dịch; doanh thu theo ngày hoàn tất. VNPay đã thanh toán được cộng tự động.</p>
        </div>
    </section>
@endsection

@section('script')
<script src="{{ asset('admin/plugins/chart.js/Chart.min.js') }}"></script>
<script>
(function () {
    var canvas = document.getElementById('agency-revenue-chart');
    if (!canvas || typeof Chart === 'undefined') return;
    var data = @json($revenueChart);
    var columns = @json($chartColumns);
    var keys = Object.keys(columns);
    var money = function (value) { return new Intl.NumberFormat('vi-VN').format(value) + ' VND'; };
    new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: { labels: data.labels, datasets: [{ label: columns[keys[0]], data: data[keys[0]], backgroundColor: '#127177', hoverBackgroundColor: '#103b52' }] },
        options: {
            responsive: true, maintainAspectRatio: false,
            legend: { display: false },
            scales: { yAxes: [{ ticks: { beginAtZero: true, callback: function (value) { return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value); } }, scaleLabel: { display: true, labelString: 'VND' } }] },
            tooltips: { callbacks: {
                label: function (item) { return columns[keys[0]] + ': ' + money(data[keys[0]][item.index]); },
                afterLabel: function (item) { return keys.slice(1).map(function (key) { return columns[key] + ': ' + money(data[key][item.index]); }); }
            } }
        }
    });
})();
</script>
@endsection
