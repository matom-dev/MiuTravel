<!DOCTYPE html>
<html lang="vi">
<head>
    <title>@yield('title', 'Mạng bán TOUR DU LỊCH trực tuyến hàng đầu Việt Vam | Miu Travel')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('seo')
        @yield('seo')
    @else
        <meta name="description" content="Miu Travel cung cấp tour du lịch, khách sạn, thuê xe và kinh nghiệm du lịch Việt Nam với thông tin rõ ràng, dễ đặt lịch.">
    @endif
    @include('page.common.head')
    @stack('structured_data')
    @yield('style')
    <link rel="stylesheet" href="{{ asset('page/css/banner-uniform.css') }}?v={{ filemtime(public_path('page/css/banner-uniform.css')) }}">
    <link rel="stylesheet" href="{{ asset('page/css/floating-contact.css') }}?v={{ filemtime(public_path('page/css/floating-contact.css')) }}">
</head>
<body>
    @include('page.common.navbar')
    @yield('content')
    @include('page.common.footer')

    <div class="floating-contact" aria-label="Liên hệ nhanh">
        <a class="floating-contact__btn floating-contact__btn--mail"
           href="mailto:letoantrung73@gmail.com"
           title="Gửi email"
           aria-label="Gửi email">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
        </a>
        <a class="floating-contact__btn floating-contact__btn--zalo"
           href="https://zalo.me/0886733538"
           target="_blank"
           rel="noopener"
           title="Liên hệ Zalo"
           aria-label="Liên hệ Zalo">
            <span class="floating-contact__zalo-text" aria-hidden="true">Zalo</span>
        </a>
        <a class="floating-contact__btn floating-contact__btn--phone"
           href="tel:0886733538"
           title="Gọi ngay"
           aria-label="Gọi ngay">
            <i class="fa fa-phone" aria-hidden="true"></i>
        </a>
        <a class="floating-contact__btn floating-contact__btn--messenger"
           href="https://m.me/toantrung.tomle"
           target="_blank"
           rel="noopener"
           title="Nhắn Messenger"
           aria-label="Nhắn Messenger">
            <span class="floating-contact__messenger-mark" aria-hidden="true">~</span>
        </a>
    </div>

    @include('page.common.script')
    @yield('script')
</body>
</html>
