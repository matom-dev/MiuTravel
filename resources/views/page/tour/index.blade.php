@extends('page.layouts.page')
@section('title', 'Danh sách Tours | Miu Travel')
@section('style')
<style>
    .search-wrap-modern {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .tour-list-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .tour-list-section {
        background: #ffffff;
        padding: 24px 0 56px !important;
    }

    .tour-list-banner>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .tour-page-hero::before {
        background: none !important;
    }

    .tour-page-hero {
        min-height: auto !important;
        background: #123f55 !important;
        background-image: none !important;
        padding: 34px 0 30px !important;
    }

    .tour-page-hero .slider-text {
        min-height: 0 !important;
        align-items: flex-start !important;
        padding: 0 !important;
    }

    .tour-page-hero .ftco-animate.pb-5 {
        padding-bottom: 0 !important;
    }

    .tour-page-hero>.container {
        width: min(100% - 28px, 1480px);
        max-width: 1480px;
        display: grid;
        grid-template-columns: minmax(320px, .72fr) minmax(720px, 1.28fr);
        gap: 24px;
        align-items: end;
    }

    .tour-hero-copy {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 0;
    }

    .tour-page-hero .search-wrap-modern {
        width: 100%;
        max-width: 1040px;
        justify-self: end;
        background: transparent;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }

    .tour-page-hero .home-search-pill {
        width: 100%;
        margin: 0;
    }

    .tour-page-hero .home-search-pill__form {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, .9fr) minmax(0, .95fr) minmax(0, .85fr) 64px;
        align-items: stretch;
        min-height: 74px;
        padding: 10px 10px 10px 34px;
        margin: 0;
        border-radius: 999px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(15, 23, 42, .18);
    }

    .tour-page-hero .home-search-pill__field {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        padding-right: 18px;
        margin-right: 18px;
        border-right: 1px solid #e3e6eb;
    }

    .tour-page-hero .home-search-pill__label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #586166;
        font-size: 14px;
        font-weight: 850;
        line-height: 1.2;
        margin-bottom: 6px;
        white-space: nowrap;
    }

    .tour-page-hero .home-search-pill__label i {
        color: #4e5559;
        font-size: 18px;
    }

    .tour-page-hero .home-search-pill__control {
        position: relative;
        min-width: 0;
    }

    .tour-page-hero .home-search-pill__control::after {
        content: "\f107";
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        color: #a6a29d;
        font-family: "FontAwesome";
        font-size: 21px;
        pointer-events: none;
    }

    .tour-page-hero .home-search-pill__control input,
    .tour-page-hero .home-search-pill__control select {
        width: 100%;
        height: 28px;
        border: 0;
        outline: 0;
        color: #080808;
        background: transparent;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.2;
        padding: 0 36px 0 0;
        appearance: none;
    }

    .tour-page-hero .home-search-pill__control input::placeholder {
        color: #080808;
        opacity: 1;
    }

    .tour-page-hero .home-search-pill__btn {
        width: 56px;
        height: 56px;
        align-self: center;
        justify-self: end;
        border: 0;
        border-radius: 50%;
        color: #fff;
        background: #ee5224;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .tour-page-hero .home-search-pill__btn:hover {
        background: #dc481d;
        transform: translateY(-1px);
    }

    .tour-banner-search {
        background: #eef3f8 !important;
        border-bottom: 0 !important;
        padding: 18px 0 20px !important;
    }

    .tour-banner-search>.container {
        width: min(100% - 44px, 1480px) !important;
        max-width: 1480px !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .tour-banner-search .search-wrap-modern {
        width: 100% !important;
        max-width: 1280px !important;
        margin: 0 auto !important;
        padding: 0 !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .tour-banner-search .home-search-pill {
        width: 100% !important;
        margin: 0 auto !important;
    }

    .tour-banner-search .home-search-pill__form {
        display: grid !important;
        grid-template-columns: minmax(0, 1.35fr) minmax(0, .9fr) minmax(0, 1fr) minmax(0, .85fr) 58px !important;
        align-items: stretch !important;
        min-height: 82px !important;
        width: 100% !important;
        padding: 12px 12px 12px 30px !important;
        margin: 0 !important;
        gap: 0 !important;
        border: 1px solid #e6edf4 !important;
        border-radius: 30px !important;
        background: #fff !important;
        box-shadow: 0 18px 46px rgba(15, 23, 42, .1) !important;
        overflow: visible !important;
    }

    .tour-banner-search .home-search-pill__field {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        min-width: 0 !important;
        padding: 0 18px 0 0 !important;
        margin: 0 18px 0 0 !important;
        border-right: 1px solid #e3e6eb !important;
        border-bottom: 0 !important;
        background: transparent !important;
    }

    .tour-banner-search .home-search-pill__label {
        display: flex !important;
        align-items: center !important;
        gap: 7px !important;
        color: #4b5563 !important;
        font-size: 13px !important;
        font-weight: 850 !important;
        line-height: 1.2 !important;
        margin: 0 0 8px !important;
        white-space: nowrap !important;
        text-transform: none !important;
        letter-spacing: 0 !important;
    }

    .tour-banner-search .home-search-pill__label i {
        color: #4e5559 !important;
        font-size: 16px !important;
    }

    .tour-banner-search .home-search-pill__control {
        position: relative !important;
        min-width: 0 !important;
    }

    .tour-banner-search .home-search-pill__control::after {
        content: "\f107" !important;
        position: absolute !important;
        right: 0 !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        color: #9aa4b2 !important;
        font-family: "FontAwesome" !important;
        font-size: 17px !important;
        pointer-events: none !important;
    }

    .tour-banner-search .home-search-pill__control:has(input)::after {
        content: none !important;
        display: none !important;
    }

    .tour-banner-search .home-search-pill__control input,
    .tour-banner-search .home-search-pill__control select {
        width: 100% !important;
        height: 28px !important;
        min-height: 28px !important;
        border: 0 !important;
        outline: 0 !important;
        color: #111827 !important;
        background: transparent !important;
        font-size: 15.5px !important;
        font-weight: 650 !important;
        line-height: 1.2 !important;
        padding: 0 28px 0 0 !important;
        appearance: none !important;
        box-shadow: none !important;
    }

    .tour-banner-search .home-search-pill__control input {
        padding-right: 0 !important;
    }

    .tour-banner-search .home-search-pill__control input::placeholder {
        color: #a8b0ba !important;
        opacity: 1 !important;
    }

    .tour-banner-search .home-search-pill__btn {
        width: 58px !important;
        height: 58px !important;
        min-width: 58px !important;
        min-height: 58px !important;
        align-self: center !important;
        justify-self: end !important;
        border: 0 !important;
        border-radius: 50% !important;
        color: #fff !important;
        background: #ee5224 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 22px !important;
        box-shadow: 0 12px 22px rgba(238, 82, 36, .26) !important;
        cursor: pointer !important;
        transition: background .2s ease, transform .2s ease, box-shadow .2s ease !important;
    }

    .tour-banner-search .home-search-pill__btn:hover {
        background: #dc481d !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 16px 28px rgba(238, 82, 36, .34) !important;
    }

    .tour-list-section .row {
        margin-left: -14px;
        margin-right: -14px;
    }

    .tour-list-section .row>[class*="col-"] {
        padding-left: 14px;
        padding-right: 14px;
    }

    .tours-count-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0 12px;
        margin-bottom: 18px;
        border-bottom: 2px solid var(--bg-light);
    }

    .tours-count-bar .count-text {
        font-size: 14px;
        color: var(--text-muted);
    }

    .tours-count-bar .count-text strong {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .06);
    }

    .empty-state i {
        font-size: 4rem;
        color: #e2e8f0;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        color: #94a3b8;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #cbd5e1;
        font-size: 14px;
    }

    @media (max-width: 767px) {

        .tour-list-section>.container {
            width: min(100% - 24px, 1480px);
        }

        .tour-list-section {
            padding: 18px 0 40px !important;
        }

        .tour-banner-search>.container {
            width: min(100% - 24px, 1480px) !important;
        }

        .tour-banner-search .home-search-pill__form {
            grid-template-columns: 1fr;
            min-height: 0;
            padding: 16px !important;
            border-radius: 18px;
            gap: 0 !important;
        }

        .tour-banner-search .home-search-pill__field {
            padding: 0 0 13px !important;
            margin: 0 0 13px !important;
            border-right: 0 !important;
            border-bottom: 1px solid #e3e6eb !important;
        }

        .tour-banner-search .home-search-pill__field:last-of-type {
            margin-bottom: 0 !important;
        }

        .tour-banner-search .home-search-pill__btn {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            font-size: 18px;
        }

        .tours-count-bar {
            align-items: flex-start;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }
    }

    @media (min-width: 768px) and (max-width: 1199px) {
        .tour-page-hero>.container {
            display: block;
        }

        .tour-hero-copy {
            margin-bottom: 18px;
        }

        .tour-banner-search .search-wrap-modern {
            max-width: none;
        }

        .tour-banner-search .home-search-pill__form {
            grid-template-columns: minmax(0, 1.25fr) minmax(0, .82fr) minmax(0, .95fr) minmax(0, .82fr) 48px !important;
            min-height: 68px;
            padding: 10px 10px 10px 18px !important;
            border-radius: 999px;
            gap: 0 !important;
        }

        .tour-banner-search .home-search-pill__field {
            padding: 0 10px 0 0 !important;
            margin: 0 10px 0 0 !important;
            border-right: 1px solid #e3e6eb !important;
            border-bottom: 0 !important;
        }

        .tour-banner-search .home-search-pill__field:nth-child(odd) {
            padding-right: 10px !important;
            margin-right: 10px !important;
            border-right: 1px solid #e3e6eb !important;
        }

        .tour-banner-search .home-search-pill__label {
            gap: 5px !important;
            font-size: 11.5px !important;
            margin-bottom: 5px !important;
        }

        .tour-banner-search .home-search-pill__label i {
            font-size: 13px !important;
        }

        .tour-banner-search .home-search-pill__control input,
        .tour-banner-search .home-search-pill__control select {
            height: 24px !important;
            min-height: 24px !important;
            font-size: 13px !important;
            padding-right: 20px !important;
        }

        .tour-banner-search .home-search-pill__control::after {
            font-size: 14px !important;
        }

        .tour-banner-search .home-search-pill__btn {
            grid-column: auto;
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            min-height: 48px !important;
            border-radius: 50%;
            font-size: 18px !important;
        }
    }
</style>
@stop
@section('content')
{{-- Hero header --}}
<section class="tour-list-banner miu-page-banner">
    <div class="container">
        <p class="breadcrumbs">
            <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i
                        class="fa fa-chevron-right"></i></a></span>
            <span>Tour</span>
        </p>
        <h1 class="miu-page-banner-title">Tour linh hoạt theo ngày bạn muốn</h1>
    </div>
</section>

<section class="banner-search-section tour-banner-search">
    <div class="container">
        <div class="search-wrap-modern ftco-animate fadeInUp ftco-animated">
            <div class="home-search-pill">
                <form action="{{ route('tour') }}" method="GET" class="home-search-pill__form">
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="tour-page-key">
                            <i class="fa fa-search"></i>
                            Chuyến du lịch
                        </label>
                        <div class="home-search-pill__control">
                            <input type="text" id="tour-page-key" name="key_tour" value="{{ request('key_tour') }}"
                                placeholder="Tìm kiếm tour bạn muốn đi" autocomplete="off">
                        </div>
                    </div>
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="tour-page-location">
                            <i class="fa fa-map-marker"></i>
                            Địa điểm
                        </label>
                        <div class="home-search-pill__control">
                            <select id="tour-page-location" name="location_id">
                                <option value="">Tất cả địa điểm</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->l_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="tour-page-price">
                            <i class="fa fa-tag"></i>
                            Khoảng giá
                        </label>
                        <div class="home-search-pill__control">
                            <select id="tour-page-price" name="price">
                                <option value="">Chọn khoảng giá</option>
                                <option value="0-1000000" {{ request('price') == '0-1000000' ? 'selected' : '' }}>0 -
                                    1.000.000đ</option>
                                <option value="1000000-2000000" {{ request('price') == '1000000-2000000' ? 'selected' : '' }}>1.000.000 - 2.000.000đ</option>
                                <option value="2000000-3000000" {{ request('price') == '2000000-3000000' ? 'selected' : '' }}>2.000.000 - 3.000.000đ</option>
                                <option value="3000000-4000000" {{ request('price') == '3000000-4000000' ? 'selected' : '' }}>3.000.000 - 4.000.000đ</option>
                                <option value="4000000-5000000" {{ request('price') == '4000000-5000000' ? 'selected' : '' }}>4.000.000 - 5.000.000đ</option>
                                <option value="5000000-6000000" {{ request('price') == '5000000-6000000' ? 'selected' : '' }}>5.000.000 - 6.000.000đ</option>
                                <option value="6000000-7000000" {{ request('price') == '6000000-7000000' ? 'selected' : '' }}>6.000.000 - 7.000.000đ</option>
                                <option value="7000000-8000000" {{ request('price') == '7000000-8000000' ? 'selected' : '' }}>7.000.000 - 8.000.000đ</option>
                                <option value="8000000-9000000" {{ request('price') == '8000000-9000000' ? 'selected' : '' }}>8.000.000 - 9.000.000đ</option>
                                <option value="9000000-10000000" {{ request('price') == '9000000-10000000' ? 'selected' : '' }}>9.000.000 - 10.000.000đ</option>
                                <option value="10000000-11000000" {{ request('price') == '10000000-11000000' ? 'selected' : '' }}>10.000.000 - 11.000.000đ</option>
                                <option value="11000000-12000000" {{ request('price') == '11000000-12000000' ? 'selected' : '' }}>11.000.000 - 12.000.000đ</option>
                                <option value="12000000-13000000" {{ request('price') == '12000000-13000000' ? 'selected' : '' }}>12.000.000 - 13.000.000đ</option>
                                <option value="13000000-14000000" {{ request('price') == '13000000-14000000' ? 'selected' : '' }}>13.000.000 - 14.000.000đ</option>
                                <option value="14000000-15000000" {{ request('price') == '14000000-15000000' ? 'selected' : '' }}>14.000.000 - 15.000.000đ</option>
                                <option value="15000000-100000000" {{ request('price') == '15000000-100000000' ? 'selected' : '' }}>Trên 15.000.000đ</option>
                            </select>
                        </div>
                    </div>
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="tour-page-duration">
                            <i class="fa fa-clock-o"></i>
                            Thời gian
                        </label>
                        <div class="home-search-pill__control">
                            <select id="tour-page-duration" name="duration">
                                <option value="">Tất cả thời gian</option>
                                <option value="1" {{ request('duration') == '1' ? 'selected' : '' }}>Tour 1 ngày</option>
                                <option value="2-3" {{ request('duration') == '2-3' ? 'selected' : '' }}>2 - 3 ngày</option>
                                <option value="4-5" {{ request('duration') == '4-5' ? 'selected' : '' }}>4 - 5 ngày</option>
                                <option value="6+" {{ request('duration') == '6+' ? 'selected' : '' }}>Từ 6 ngày</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="home-search-pill__btn" aria-label="Tìm kiếm tour">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Tours List --}}
<section class="tour-list-section">
    <div class="container">

        <div class="tours-count-bar">
            <div class="count-text">
                Tìm thấy <strong>{{ $tours->total() }}</strong> tour phù hợp
            </div>
            <div style="font-size:13px;color:var(--text-muted);">
                <i class="fa fa-compass" style="color:var(--primary);"></i>
                Sắp xếp theo: Mới nhất
            </div>
        </div>

        <div class="row">
            @if($tours->count() > 0)
                @foreach($tours as $tour)
                    @include('page.common.itemTour', compact('tour'))
                @endforeach
            @else
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fa fa-search"></i>
                        <h4>Không tìm thấy tour nào</h4>
                        <p>Thử thay đổi điều kiện tìm kiếm để xem thêm kết quả.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="row mt-5">
            <div class="col text-center">
                <div class="block-27">
                    {{ $tours->links('page.pagination.default') }}
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
@stop
