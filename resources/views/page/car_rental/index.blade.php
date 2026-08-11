@extends('page.layouts.page')
@section('title', 'Thuê xe du lịch | Miu Travel')
@section('style')
<style>
    .car-rental-hero {
        position: relative;
        min-height: auto;
        display: flex;
        align-items: stretch;
        background: #123f55;
        padding: 34px 0 30px;
    }

    .car-rental-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: none;
    }

    .car-rental-hero__inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: minmax(320px, 1fr) minmax(560px, 680px);
        gap: 24px;
        align-items: end;
        padding: 0;
        color: #fff;
    }

    .car-rental-hero h1 {
        color: #fff;
        font-size: clamp(1.9rem, 3.1vw, 3rem);
        font-weight: 900;
        line-height: 1.12;
        margin: 0 0 12px;
        letter-spacing: 0;
    }

    .car-rental-hero p {
        max-width: 620px;
        color: rgba(255, 255, 255, .86);
        font-size: 18px;
        line-height: 1.7;
        margin: 0;
        white-space: normal;
    }

    .car-rental-hero__copy {
        margin-bottom: 0;
    }

    .car-rental-hero .breadcrumbs {
        max-width: none;
        margin: 0 0 12px;
        line-height: 1.4;
        white-space: normal;
    }

    .car-rental-hero .breadcrumbs a,
    .car-rental-hero .breadcrumbs span {
        color: rgba(255, 255, 255, .86);
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
    }

    .car-rental-section {
        background: #f5f7fb;
        padding: 34px 0 70px;
    }

    .car-rental-hero>.container {
        width: min(100% - 28px, 1480px);
        max-width: 1480px;
    }

    .car-rental-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .car-filter {
        width: 100%;
        max-width: 680px;
        justify-self: end;
        background: #fff;
        border: 1px solid #e7ebf0;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 0;
        box-shadow: 0 18px 48px rgba(0, 0, 0, .18);
    }

    .car-filter form {
        display: grid;
        grid-template-columns: 1.4fr 1fr .8fr auto;
        gap: 8px;
        align-items: end;
    }

    .car-filter form label {
        display: block;
        color: #64748b;
        font-size: 12px;
        line-height: 1.25;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .car-filter form input,
    .car-filter form select {
        width: 100%;
        height: 42px;
        border: 1px solid #dde5ee;
        border-radius: 8px;
        padding: 0 12px;
        color: #172033;
        font-size: 14px;
        line-height: 1.35;
        background: #fbfcfe;
    }

    .car-filter form button {
        height: 42px;
        border: 0;
        border-radius: 8px;
        padding: 0 16px;
        color: #fff;
        font-weight: 850;
        font-size: 14px;
        background: linear-gradient(135deg, #f15d30, #d44820);
        cursor: pointer;
        box-shadow: 0 10px 22px rgba(241, 93, 48, .22);
    }

    .car-count-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        color: #64748b;
        font-size: 14px;
    }

    .car-count-bar strong {
        color: #f15d30;
        font-size: 1.1rem;
    }

    .car-rental-section .row {
        margin-left: -7px;
        margin-right: -7px;
    }

    .car-rental-section .row>[class*="col-"] {
        padding-left: 7px;
        padding-right: 7px;
        margin-bottom: 22px !important;
    }

    .car-rental-card {
        height: 100%;
        cursor: pointer;
    }

    .car-rental-card .hotel-card__img {
        height: 180px !important;
        cursor: pointer;
    }

    .car-rental-card .hotel-card__img-overlay {
        display: none;
    }

    .car-rental-contact {
        min-width: 0;
    }

    .car-rental-contact span,
    .car-rental-contact strong {
        display: block;
    }

    .car-rental-contact span {
        color: #8a94a3;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .car-rental-contact strong {
        color: #12947f;
        font-size: 13px;
        font-weight: 900;
        line-height: 1.35;
    }

    .car-empty {
        background: #fff;
        border: 1px dashed #d8dee8;
        border-radius: 12px;
        padding: 52px 20px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 991px) {
        .car-rental-hero__inner {
            display: block;
        }

        .car-rental-hero__copy {
            margin-bottom: 18px;
        }

        .car-rental-hero p {
            white-space: normal;
        }

        .car-filter {
            max-width: none;
        }

        .car-filter form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 575px) {

        .car-rental-hero>.container,
        .car-rental-section>.container {
            width: min(100% - 24px, 1480px);
        }

        .car-filter {
            padding: 18px;
        }

        .car-filter form {
            grid-template-columns: 1fr;
        }

        .car-rental-card .hotel-card__img {
            height: 195px !important;
        }

        .car-count-bar {
            align-items: flex-start;
            flex-direction: column;
            gap: 6px;
        }
    }
</style>
@stop

@section('content')
<section class="car-rental-hero">
    <div class="container">
        <div class="car-rental-hero__inner">
            <div class="car-rental-hero__copy">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i
                                class="fa fa-chevron-right"></i></a></span>
                    <span>Thuê xe <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1>Thuê xe du lịch</h1>
            </div>
        </div>
    </div>
</section>

<section class="banner-search-section car-banner-search">
    <div class="container">
        <div class="search-wrap-modern car-filter">
            <div class="home-search-pill">
                <form method="GET" action="{{ route('car.rental') }}"
                    class="home-search-pill__form home-search-pill__form--car">
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="car-page-key">
                            <i class="fa fa-search"></i>
                            Tìm xe
                        </label>
                        <div class="home-search-pill__control">
                            <input type="text" id="car-page-key" name="key_car" value="{{ request('key_car') }}"
                                placeholder="Nhập tên xe hoặc dịch vụ" autocomplete="off">
                        </div>
                    </div>
                    <div class="home-search-pill__field">
                        <label class="home-search-pill__label" for="car-page-seats">
                            <i class="fa fa-users"></i>
                            Số chỗ
                        </label>
                        <div class="home-search-pill__control">
                            <select id="car-page-seats" name="seats">
                                <option value="">Bất kỳ</option>
                                @foreach([4, 5, 7, 16, 29, 45] as $seat)
                                    <option value="{{ $seat }}" {{ request('seats') == $seat ? 'selected' : '' }}>
                                        {{ $seat }} chỗ
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="home-search-pill__btn" aria-label="Tìm kiếm xe">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="car-rental-section">
    <div class="container">
        <div class="car-count-bar">
            <div>Tìm thấy <strong>{{ $carRentals->total() }}</strong> dịch vụ thuê xe</div>
            <div><i class="fa fa-car" style="color:#f15d30;"></i> Sắp xếp theo: Mới nhất</div>
        </div>

        <div class="row">
            @forelse($carRentals as $carRental)
                @php
                    $carImages = [];
                    if ($carRental->cr_image) {
                        $carImages[] = asset(pare_url_file($carRental->cr_image));
                    }
                    foreach (($carRental->cr_album_images ?: []) as $albumImage) {
                        $carImages[] = asset(pare_url_file($albumImage));
                    }
                    if (empty($carImages)) {
                        $carImages[] = asset('admin/dist/img/no-image.png');
                    }
                    $carDetailUrl = route('car.rental.detail', ['id' => $carRental->id, 'slug' => safeTitle($carRental->cr_name)]);
                @endphp
                <div class="col-sm-6 col-lg-3 ftco-animate fadeInUp ftco-animated" style="margin-bottom:22px;">
                    <article class="hotel-card car-rental-card js-car-card-link" data-card-url="{{ $carDetailUrl }}"
                        tabindex="0" role="link" aria-label="Xem xe {{ $carRental->cr_name }}">
                        <a href="{{ $carDetailUrl }}" class="hotel-card__img" title="Xem xe {{ $carRental->cr_name }}"
                            style="background-image:url({{ $carImages[0] }});">
                            <div class="hotel-card__img-overlay"></div>
                            <div class="hotel-card__stars">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                            </div>
                        </a>

                        <div class="hotel-card__body">
                            <div class="hotel-card__location">
                                <i class="fa fa-map-marker"></i>
                                <span>{{ optional($carRental->location)->l_name ?: 'Linh hoạt' }}</span>
                            </div>

                            <h3 class="hotel-card__title">
                                <a href="{{ $carDetailUrl }}" title="{{ $carRental->cr_name }}">
                                    {{ the_excerpt($carRental->cr_name, 70) }}
                                </a>
                            </h3>

                            @if($carRental->cr_description)
                                <p class="hotel-card__desc">{!! the_excerpt(strip_tags($carRental->cr_description), 95) !!}</p>
                            @endif

                            <div class="hotel-card__amenities">
                                <span class="hotel-card__amenity"><i class="fa fa-users"></i>
                                    {{ $carRental->cr_number_seats ? $carRental->cr_number_seats . ' chỗ' : 'Số chỗ' }}</span>
                                <span class="hotel-card__amenity"><i class="fa fa-cog"></i>
                                    {{ $carRental->cr_transmission ?: 'Hộp số' }}</span>
                                <span class="hotel-card__amenity"><i class="fa fa-tint"></i>
                                    {{ $carRental->cr_fuel ?: 'Nhiên liệu' }}</span>
                            </div>

                            <div class="hotel-card__divider"></div>

                            <div class="hotel-card__footer">
                                <div class="car-rental-contact">
                                    <span>Báo giá</span>
                                    <strong>Liên hệ trực tiếp</strong>
                                </div>
                                <a href="{{ $carDetailUrl }}" class="hotel-card__btn">
                                    Xem thông tin <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="car-empty">
                        <i class="fa fa-car" style="font-size:34px;color:#f15d30;margin-bottom:10px;display:block;"></i>
                        Chưa có dịch vụ thuê xe nào phù hợp.
                    </div>
                </div>
            @endforelse
        </div>

        @if($carRentals->hasPages())
            <div class="block-27 mt-5 d-flex justify-content-center">
                {{ $carRentals->links('page.pagination.default') }}
            </div>
        @endif
    </div>
</section>
<script>
    document.addEventListener('click', function (event) {
        var card = event.target.closest('.js-car-card-link');
        if (!card) return;
        if (event.target.closest('a, button, input, select, textarea')) return;

        var url = card.getAttribute('data-card-url');
        if (url) window.location.href = url;
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') return;
        var card = event.target.closest('.js-car-card-link');
        if (!card) return;

        var url = card.getAttribute('data-card-url');
        if (url) window.location.href = url;
    });
</script>
@stop