@extends('page.layouts.page')
@section('title', 'Thanh toán VNPay | Miu Travel')
@section('content')
<section class="container" style="padding:60px 16px; max-width:760px;">
    <div class="card" style="padding:32px; border-radius:18px;">
        <h1 style="font-size:28px;">Thanh toán tour qua VNPay</h1>
        @if(session('error')) <div class="alert alert-danger" role="alert">{{ session('error') }}</div> @endif
        <p>Mã đặt tour: <strong>{{ $book->display_code }}</strong></p>
        <p>{{ $book->tour->t_title ?? 'Tour đã đặt' }}</p>
        <p>Tổng thanh toán: <strong>{{ number_format($book->total_price, 0, ',', '.') }} VNĐ</strong></p>
        <p>Bạn sẽ được chuyển sang VNPay để chọn ngân hàng, thẻ hoặc quét mã QR.</p>
        @if($configured)
            <form method="POST" action="{{ route('vnpay.pay', $book->id) }}">
                @csrf
                <button class="btn btn-primary" type="submit">Thanh toán qua VNPay</button>
            </form>
        @else
            <div class="alert alert-info">Thanh toán VNPay hiện chưa sẵn sàng. Vui lòng liên hệ Miu Travel để được hỗ trợ.</div>
        @endif
        <a href="{{ route('my.tour') }}" style="display:inline-block;margin-top:20px;">Quay lại Tour đã đặt</a>
    </div>
</section>
@endsection
