@extends('admin.layouts.main')
@section('title', 'Đại lý du lịch · '.$agency->name)
@section('style-css')
<style>
.agency-page{padding:20px;color:#26394a}.agency-hero{background:linear-gradient(115deg,#103b52,#127177);color:#fff;padding:24px;border-radius:15px;margin-bottom:20px}.agency-hero h1{font-size:26px;font-weight:700;margin:0}.agency-hero p{margin:5px 0 0;opacity:.9}.agency-nav{display:flex;gap:8px;flex-wrap:wrap;margin-top:18px}.agency-nav a{border:1px solid #ffffff8c;border-radius:25px;padding:6px 12px;color:#fff;font-size:13px}.agency-nav a:hover{background:#fff;color:#103b52;text-decoration:none}.agency-panel{background:#fff;border:1px solid #dfe8ed;border-radius:13px;margin-bottom:19px;box-shadow:0 5px 18px #123b520a;overflow:hidden}.agency-panel__head{padding:17px 21px;border-bottom:1px solid #e7edf0;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}.agency-panel__head h2{font-size:18px;font-weight:700;margin:0}.agency-panel__body{padding:21px}.agency-fields,.agency-metrics{display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:13px}.agency-page label{display:block;font-size:13px;font-weight:600;color:#425b68}.agency-page label .form-control{margin-top:5px;font-weight:400}.agency-metric{background:#f3faf9;border:1px solid #dce9e9;border-radius:10px;padding:15px}.agency-metric span{color:#58717c;font-size:13px;display:block;min-height:32px}.agency-metric strong{font-size:24px;display:block;color:#103b52}.agency-metric.money{background:#f2f7fc;border-color:#dae8f4}.agency-page details{border:1px solid #e2e9ed;border-radius:9px;margin-top:11px}.agency-page summary{cursor:pointer;padding:14px 16px;font-weight:600}.agency-page details[open]>summary{border-bottom:1px solid #e2e9ed}.agency-inner{padding:17px}.agency-scroll{overflow:auto}.agency-table{width:100%;min-width:630px}.agency-table th,.agency-table td{padding:11px;border-bottom:1px solid #e8edf0;vertical-align:middle}.agency-table th{background:#f8fafb;color:#5f7580;font-size:12px}.agency-muted{color:#617985;font-size:13px}.agency-logo{width:78px;height:78px;object-fit:cover;border-radius:10px;border:1px solid #dce7eb}.agency-progress{height:8px;background:#e5eff0;border-radius:20px;overflow:hidden}.agency-progress span{height:100%;display:block;background:#189098}@media(max-width:650px){.agency-page{padding:10px}.agency-panel__body{padding:15px}}
</style>
<style>
.agency-nav a.active{background:#fff;color:#103b52;font-weight:700}
</style>
@endsection
@section('content')
<div class="agency-page">
    <header class="agency-hero">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:15px">
            <div><small>{{ $agency->name }} · NHÂN SỰ CÔNG TY</small><h1>@yield('agency-heading')</h1><p>@yield('agency-description')</p></div>
            @yield('agency-action')
        </div>
        <nav class="agency-nav" aria-label="Điều hướng đại lý">
            <a class="{{ request()->routeIs('agency.profile.show') ? 'active' : '' }}" href="{{ route('agency.profile.show') }}">Hồ sơ đại lý</a>
            <a class="{{ request()->routeIs('agency.dashboard') ? 'active' : '' }}" href="{{ route('agency.dashboard') }}">Thống kê & tài chính</a>
            <a class="{{ request()->routeIs('agency.tours.*') || request()->routeIs('agency.tour.form.*') ? 'active' : '' }}" href="{{ route('agency.tours.index') }}">Tour & khởi hành</a>
            <a class="{{ request()->routeIs('agency.bookings.*') ? 'active' : '' }}" href="{{ route('agency.bookings.index') }}">Đăng ký & hành khách</a>
        </nav>
    </header>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @yield('agency-body')
</div>
@endsection
