@extends('admin.layouts.main')
@section('title', 'Phí nền tảng từ đại lý')
@section('content')
<section class="content-header"><h1>Phí nền tảng từ đại lý</h1></section>
<section class="content">
    <div class="alert alert-info">Phí đã ghi nhận từ đơn hoàn tất, theo ngày ghi nhận doanh thu và tỷ lệ lưu trên từng đơn. Đây là số tiền nền tảng được hưởng để đối soát, chưa xác nhận tiền thực nhận. Đơn hủy và tour của nền tảng không được tính.</div>
    <div class="card"><div class="card-body">
        @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
        <form method="get" action="{{ route('admin.platform-fee.report') }}" class="row align-items-end">
            <div class="col-md-3 form-group"><label for="fee-month">Tháng</label><select id="fee-month" class="form-control" name="month"><option value="">Cả năm</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}" @selected((string)$month===(string)$m)>Tháng {{ $m }}</option>@endfor</select></div>
            <div class="col-md-2 form-group"><label for="fee-year">Năm</label><input id="fee-year" class="form-control" type="number" min="2000" max="2100" name="year" value="{{ $year }}" required></div>
            <div class="col-md-4 form-group"><label for="fee-agency">Đại lý</label><select id="fee-agency" class="form-control" name="agency_id"><option value="">Tất cả đại lý</option>@foreach($agencies as $agency)<option value="{{ $agency->id }}" @selected((string)$agencyId===(string)$agency->id)>{{ $agency->name }} (#{{ $agency->id }})</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><button class="btn btn-primary">Lọc báo cáo</button> <a href="{{ route('admin.platform-fee.report') }}" class="btn btn-link">Đặt lại</a></div>
        </form>
    </div></div>
    <div class="row">
        @foreach(['fee'=>'Phí nền tảng đã ghi nhận','count'=>'Đơn hoàn tất','revenue'=>'Giá trị đơn hoàn tất','net'=>'Đại lý được hưởng'] as $key=>$label)
            <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">{{ $label }}</div><strong class="h4 d-block mt-2">{{ number_format($totals[$key],0,',','.') }}{{ $key==='count' ? '' : ' ₫' }}</strong></div></div></div>
        @endforeach
    </div>
    <div class="card"><div class="card-header"><h2 class="h5 mb-0">Theo từng đại lý</h2></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Đại lý</th><th>Đơn hoàn tất</th><th>Giá trị đơn</th><th>Phí nền tảng</th><th>Đại lý được hưởng</th><th></th></tr></thead><tbody>
        @forelse($groups as $group)
            <tr><td>{{ $agencies->get($group['agency_id'])?->name ?? 'Đại lý không còn hồ sơ' }} (#{{ $group['agency_id'] }})</td><td>{{ $group['count'] }}</td>@foreach(['revenue','fee','net'] as $key)<td>{{ number_format($group[$key],0,',','.') }} ₫</td>@endforeach<td><a href="{{ route('admin.platform-fee.report', ['month'=>$month ?? '', 'year'=>$year, 'agency_id'=>$group['agency_id']]) }}#fee-bookings">Xem đơn</a></td></tr>
        @empty<tr><td colspan="6" class="text-center text-muted p-4">Chưa có phí nền tảng ghi nhận trong kỳ đã chọn.</td></tr>@endforelse
    </tbody></table></div></div>
    <div class="card" id="fee-bookings"><div class="card-header"><h2 class="h5 mb-0">Chi tiết đơn đối soát</h2></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Mã đơn / Tour</th><th>Đại lý</th><th>Ngày ghi nhận</th><th>Giá trị đơn</th><th>Tỷ lệ phí</th><th>Phí nền tảng</th><th>Đại lý được hưởng</th></tr></thead><tbody>
        @forelse($bookings as $booking)
            @php $fee = round($booking->total_price * $booking->commission_basis_points / 10000); @endphp
            <tr><td>{{ $booking->display_code }}<div class="small text-muted">{{ $booking->tour?->t_title ?? 'Tour không còn hiển thị' }}</div></td><td>{{ $booking->agency?->name ?? 'Đại lý #'.$booking->agency_id }}</td><td>{{ $booking->revenue_recognized_at->format('d/m/Y H:i') }}</td><td>{{ number_format($booking->total_price,0,',','.') }} ₫</td><td>{{ $booking->commission_basis_points / 100 }}%</td><td>{{ number_format($fee,0,',','.') }} ₫</td><td>{{ number_format($booking->total_price - $fee,0,',','.') }} ₫</td></tr>
        @empty<tr><td colspan="7" class="text-center text-muted p-4">Không có đơn phù hợp.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer">{{ $bookings->links() }}</div></div>
</section>
@endsection
