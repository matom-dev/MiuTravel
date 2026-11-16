@extends('admin.layouts.main')
@section('title', 'Phí nền tảng')
@section('content')
<section class="content-header"><h1>Phí nền tảng</h1></section>
<section class="content"><div class="card"><div class="card-body">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <p><a href="{{ route('admin.platform-fee.report') }}">Xem báo cáo phí từ đại lý</a></p>
    <p>Áp dụng chung cho mọi đại lý. Khách thanh toán giá tour, không cộng thêm phí. Phí được khấu trừ từ giá trị đơn khi ghi nhận hoàn tất.</p>
    <p>Thay đổi tỷ lệ chỉ áp dụng cho đơn mới. Đơn đã tạo giữ nguyên tỷ lệ, kể cả khi hoàn tất sau ngày đổi phí. Đơn hủy không ghi nhận phí.</p>
    <form method="post" action="{{ route('admin.platform-fee.update') }}">@csrf
        <div class="form-group"><label for="percentage">Tỷ lệ phí (%)</label><input id="percentage" class="form-control" style="max-width:240px" type="number" name="percentage" min="0" max="100" step="0.01" value="{{ old('percentage', $percentage) }}" required></div>
        <button class="btn btn-primary">Lưu tỷ lệ phí</button>
    </form>
    <p class="text-muted mt-3">Nếu đại lý trực tiếp thu tiền, phí khấu trừ là số tiền cần đối soát với nền tảng; hệ thống không tự trích tiền từ tài khoản đại lý.</p>
</div></div></section>
@endsection
