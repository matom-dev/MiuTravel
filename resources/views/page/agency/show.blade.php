@extends('page.layouts.page')
@section('title', $agency->name.' | Đại lý du lịch | Miu Travel')
@section('seo')
<meta name="description" content="Tìm hiểu về {{ $agency->name }} và {{ $tourCount }} tour du lịch đang giới thiệu trên Miu Travel.">
@endsection
@section('style')
<style>
.public-agency-hero{background:#123f55;color:#fff;padding:38px 0 42px}.public-agency-hero .container,.public-agency-section .container{width:min(100% - 28px,1480px);max-width:1480px}.public-agency-hero a{color:#fff;text-decoration:underline}.public-agency-hero h1{color:#fff;font-weight:800;font-size:clamp(30px,4vw,48px);margin:10px 0 0}.public-agency-section{background:#f2f6f8;padding:34px 0 65px}.public-agency-card{background:#fff;border:1px solid #e1e9ed;border-radius:14px;box-shadow:0 12px 28px #122e410c;padding:26px;margin-bottom:26px}.public-agency-grid{display:grid;grid-template-columns:100px minmax(0,1fr);gap:22px}.public-agency-logo{width:100px;height:100px;border:1px solid #dce6e9;border-radius:13px;display:flex;align-items:center;justify-content:center;overflow:hidden;color:#126b70;background:#f8fafb;font-size:34px}.public-agency-logo img{width:100%;height:100%;object-fit:contain}.public-agency-card h2{color:#1a3042;font-weight:800;font-size:24px}.public-agency-desc{color:#4e6270;white-space:pre-line;line-height:1.65}.public-agency-stats{display:flex;gap:12px;flex-wrap:wrap;margin:20px 0}.public-agency-stat{border:1px solid #dce9e9;border-radius:10px;background:#f4faf9;padding:14px 18px;min-width:170px}.public-agency-stat strong{display:block;font-size:26px;color:#126b70;line-height:1.1}.public-agency-stat span{display:block;color:#4c6873;font-size:13px;margin-top:4px}.public-agency-contacts{display:flex;flex-wrap:wrap;gap:12px 20px}.public-agency-contacts span,.public-agency-contacts a{color:#375768;overflow-wrap:anywhere}.public-agency-contacts i{color:#ed5b2b;margin-right:5px}.public-agency-section h3{font-weight:800;color:#1a3042;font-size:25px}
.public-agency-list{background:#fff;border:1px solid #e1e9ed;border-radius:14px;padding:0 24px}.public-agency-tour{display:grid;grid-template-columns:220px minmax(0,1fr);gap:24px;padding:22px 0;border-bottom:1px solid #e6edf0}.public-agency-tour:last-child{border-bottom:0}.public-agency-tour__image{display:block;width:100%;height:150px;border-radius:9px;overflow:hidden;background:#e9f0f3}.public-agency-tour__image img{width:100%;height:100%;object-fit:cover}.public-agency-tour__body{min-width:0}.public-agency-tour__organizer{color:#54707c;font-size:13px;margin:0 0 8px}.public-agency-tour__organizer a{color:#126b70;font-weight:700}.public-agency-tour__title{font-size:21px;font-weight:750;line-height:1.35;margin:0 0 9px}.public-agency-tour__title a{color:#20384a}.public-agency-tour__title a:hover{color:#db522b}.public-agency-tour__rating{display:flex;align-items:center;gap:10px;flex-wrap:wrap;color:#637785;font-size:13px;margin-bottom:9px}.public-agency-tour__rating .fa-star{color:#efa524}.public-agency-tour__review{border-left:3px solid #d7e8e9;padding-left:12px;color:#4c6170;margin:8px 0 10px;line-height:1.5}.public-agency-tour__review p{margin:0 0 3px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.public-agency-tour__review small{color:#70818b}.public-agency-tour__link{font-weight:700;color:#d7532d;font-size:13px}.public-agency-tour__empty{color:#71838d;font-size:14px;margin:0 0 10px}@media(max-width:620px){.public-agency-grid{grid-template-columns:1fr}.public-agency-card{padding:19px}.public-agency-list{padding:0 16px}.public-agency-tour{grid-template-columns:110px minmax(0,1fr);gap:14px}.public-agency-tour__image{height:100px}.public-agency-tour__title{font-size:17px}.public-agency-tour__review{font-size:13px}}@media(max-width:400px){.public-agency-tour{grid-template-columns:1fr}.public-agency-tour__image{height:150px}}
.public-agency-section{background:#fff}
.public-agency-list{background:transparent;border:0;border-radius:0;padding:0}
</style>
@endsection
@section('content')
<section class="public-agency-hero"><div class="container"><div><a href="{{ route('tour') }}">Tour du lịch</a> / Đại lý tổ chức</div><h1>{{ $agency->name }}</h1></div></section>
<section class="public-agency-section"><div class="container">
    <div class="public-agency-card"><div class="public-agency-grid">
        <div class="public-agency-logo">@if($agency->logo)<img src="{{ asset(pare_url_file($agency->logo)) }}" alt="Logo {{ $agency->name }}">@else<i class="fa fa-building-o" aria-hidden="true"></i>@endif</div>
        <div><h2>Về {{ $agency->name }}</h2>
            @if($agency->description)<p class="public-agency-desc">{{ $agency->description }}</p>@endif
            <div class="public-agency-stats"><div class="public-agency-stat"><strong>{{ number_format($tourCount) }}</strong><span>Tour công khai do đại lý tổ chức</span></div></div>
            <div class="public-agency-contacts">@if($agency->address)<span><i class="fa fa-map-marker"></i> {{ $agency->address }}</span>@endif @if($agency->phone)<a href="tel:{{ preg_replace('/[^0-9+]/','',$agency->phone) }}"><i class="fa fa-phone"></i> {{ $agency->phone }}</a>@endif @if($agency->email)<a href="mailto:{{ $agency->email }}"><i class="fa fa-envelope-o"></i> {{ $agency->email }}</a>@endif</div>
        </div>
    </div></div>
    <h3 class="mb-3">Tour và đánh giá của khách hàng</h3>
    <div class="public-agency-list">
        @foreach($tours as $tour)
            @php $tourUrl = route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]); @endphp
            <article class="public-agency-tour">
                <a class="public-agency-tour__image" href="{{ $tourUrl }}" aria-label="Xem tour {{ $tour->t_title }}"><img src="{{ $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $tour->t_title }}" loading="lazy"></a>
                <div class="public-agency-tour__body">
                    <p class="public-agency-tour__organizer"><i class="fa fa-building-o" aria-hidden="true"></i> Tổ chức bởi <a href="{{ route('agency.public', $agency->id) }}">{{ $agency->name }}</a></p>
                    <h4 class="public-agency-tour__title"><a href="{{ $tourUrl }}">{{ $tour->t_title }}</a></h4>
                    @if($tour->review_count)
                        <div class="public-agency-tour__rating">
                            @if($tour->average_rating)<span><i class="fa fa-star" aria-hidden="true"></i> <strong>{{ number_format($tour->average_rating, 1, ',', '.') }}/5</strong></span>@endif
                            <span>{{ $tour->review_count }} đánh giá đã duyệt</span>
                        </div>
                        @if($tour->latestApprovedReview)
                            <div class="public-agency-tour__review"><p>“{{ $tour->latestApprovedReview->cm_content }}”</p><small>{{ $tour->latestApprovedReview->user?->name ?: 'Khách hàng' }}</small></div>
                        @endif
                    @else
                        <p class="public-agency-tour__empty">Chưa có đánh giá từ khách hàng.</p>
                    @endif
                    <a class="public-agency-tour__link" href="{{ $tourUrl }}#comments">Xem đánh giá và thông tin tour <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </article>
        @endforeach
    </div>
    <div class="mt-4">{{ $tours->links() }}</div>
</div></section>
@endsection
