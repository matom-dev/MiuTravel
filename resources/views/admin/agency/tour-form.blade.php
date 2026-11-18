@extends('admin.layouts.main')
@section('title', $tour ? 'Chỉnh sửa tour · '.$tour->t_title : 'Tạo tour mới')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div>
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Đại lý du lịch</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('agency.tours.index') }}">Tour của tôi</a></li>
                    <li class="breadcrumb-item active">{{ $tour ? 'Chỉnh sửa' : 'Tạo mới' }}</li>
                </ol>
                <h1 class="h4 font-weight-bold mb-1">{{ $tour ? 'Chỉnh sửa tour' : 'Tạo tour mới' }}</h1>
                <p class="text-muted mb-0">{{ $agency->name }} · Giá và chính sách mới chỉ áp dụng cho đăng ký được tạo sau khi lưu.</p>
            </div>
            <a href="{{ route('agency.tours.index') }}" class="btn btn-outline-secondary mt-2"><i class="fas fa-arrow-left mr-1"></i> Về danh sách tour</a>
        </div>
    </div>
</section>
<section class="content">
    @if(session('success'))<div class="alert alert-success mx-3">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger mx-3">Vui lòng kiểm tra các trường chưa hợp lệ. {{ $errors->first() }}</div>@endif
    <div class="mx-3">@include('agency.platform-fee-notice')</div>
    @include('admin.tour.form')
    @if($tour)
        @include('agency.departures')
        <form method="post" action="{{ route('agency.tour.delete', $tour->id) }}" class="mx-3 mb-4" onsubmit="return confirm('Bạn có chắc muốn xóa tour này? Thao tác này không thể hoàn tác.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash mr-1" aria-hidden="true"></i> Xóa tour</button>
        </form>
    @else
        <div class="alert alert-info mx-3">Lưu tour trước, sau đó nhập ngày đi, ngày về và số chỗ trong mục đợt khởi hành ngay bên dưới.</div>
    @endif
</section>
@endsection
