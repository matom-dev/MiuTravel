@extends('page.layouts.page')
@section('title', 'Kết quả thanh toán | Miu Travel')
@section('content')
<section class="container" style="padding:60px 16px;max-width:760px;">
    <div class="card" style="padding:32px;border-radius:18px;">
        <h1 style="font-size:28px;">Kết quả thanh toán VNPay</h1>
        <p role="status">{{ $message }}</p>
        <a class="btn btn-primary" href="{{ route('my.tour') }}">Xem Tour đã đặt</a>
    </div>
</section>
@endsection
