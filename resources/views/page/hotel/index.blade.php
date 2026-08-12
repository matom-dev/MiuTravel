@extends('page.layouts.page')
@section('title', 'Danh sách Khách Sạn | Miu Travel')
@section('style')
<style>
    .search-wrap-modern {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        overflow: hidden;
    }

    .hotel-search-section>.container {
        width: min(100% - 28px, 1480px);
        max-width: 1480px;
    }

    .hotel-list-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .hotel-search-section {
        background: var(--bg-light);
        padding: 30px 0 0 !important;
    }

    .hotel-page-hero::before {
        background: none !important;
    }

    .hotel-page-hero {
        min-height: auto !important;
        background: #123f55 !important;
        background-image: none !important;
        padding: 34px 0 30px !important;
    }

    .hotel-page-hero>.container {
        width: min(100% - 28px, 1480px);
        max-width: 1480px;
        display: grid;
        grid-template-columns: minmax(320px, 1fr) minmax(620px, 780px);
        gap: 24px;
        align-items: end;
    }

    .hotel-page-hero .slider-text {
        min-height: 0 !important;
        align-items: flex-start !important;
        padding: 0 !important;
    }

    .hotel-page-hero .ftco-animate.pb-5 {
        padding-bottom: 0 !important;
    }

    .hotel-hero-copy {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 0;
    }

    .hotel-page-hero .search-wrap-modern {
        width: 100%;
        max-width: 780px;
        justify-self: end;
        border-radius: 8px;
        box-shadow: 0 18px 48px rgba(0, 0, 0, .18);
    }

    .hotel-page-hero .search-property-1>.row {
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr 140px;
        align-items: stretch;
        margin: 0;
    }

    .hotel-page-hero .search-property-1>.row>[class*="col-"] {
        max-width: none;
        padding: 0;
    }

    .hotel-page-hero .search-property-1 .form-group {
        min-height: 76px;
        padding: 12px 14px !important;
    }

    .hotel-page-hero .hotel-search-compact label {
        font-size: 12px;
        margin-bottom: 6px !important;
    }

    .hotel-page-hero .hotel-search-compact .form-control {
        height: 34px !important;
        min-height: 34px;
        font-size: 13px;
    }

    .hotel-page-hero .hotel-search-submit {
        height: 42px !important;
        min-width: 112px;
        border-radius: 8px !important;
        font-size: 14px !important;
        padding: 0 16px !important;
    }

    .hotel-list-section {
        background: var(--bg-light);
        padding: 24px 0 56px !important;
    }

    .hotel-banner-search {
        background: #eef3f8 !important;
        border-bottom: 0 !important;
        padding: 18px 0 16px !important;
        position: relative;
        z-index: 20;
    }

    .hotel-banner-search>.container {
        width: min(100% - 44px, 1480px) !important;
        max-width: 1480px !important;
        margin: 0 auto !important;
    }

    .banner-search-section.hotel-banner-search .search-wrap-modern {
        margin: 0 auto !important;
        max-width: none !important;
        overflow: visible !important;
        width: 100% !important;
    }

    .hotel-connect-search {
        display: grid;
        grid-template-columns: minmax(240px, 1.45fr) minmax(155px, .82fr) minmax(155px, .82fr) minmax(220px, 1fr) 162px;
        min-height: 76px;
        background: #fff;
        border: 2px solid #f15d30;
        border-radius: 8px;
        box-shadow: 0 14px 38px rgba(15, 23, 42, .12);
    }

    .hotel-connect-search__field {
        min-width: 0;
        padding: 12px 16px;
        border-right: 1px solid #dfe6ed;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .hotel-connect-search__field label {
        align-items: center;
        color: #344054;
        display: flex;
        font-size: 12px;
        font-weight: 800;
        gap: 7px;
        line-height: 1.2;
        margin: 0 0 7px;
    }

    .hotel-connect-search__field label i {
        color: #f15d30;
        font-size: 14px;
    }

    .hotel-connect-search__field input,
    .hotel-guests-trigger {
        background: transparent;
        border: 0;
        color: #101828;
        font-size: 14px;
        font-weight: 650;
        height: 25px;
        line-height: 25px;
        min-width: 0;
        outline: 0;
        padding: 0;
        width: 100%;
    }

    .hotel-connect-search__field input::placeholder {
        color: #98a2b3;
        font-weight: 500;
    }

    .hotel-connect-search__field--guests {
        position: relative;
    }

    .hotel-guests-trigger {
        align-items: center;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        text-align: left;
    }

    .hotel-guests-trigger .fa-angle-down {
        color: #667085;
        transition: transform .2s ease;
    }

    .hotel-guests-trigger[aria-expanded="true"] .fa-angle-down {
        transform: rotate(180deg);
    }

    .hotel-guests-popover {
        background: #fff;
        border: 1px solid #dfe6ed;
        border-radius: 8px;
        box-shadow: 0 18px 44px rgba(15, 23, 42, .18);
        min-width: 300px;
        padding: 10px 16px;
        position: absolute;
        right: 8px;
        top: calc(100% + 8px);
        z-index: 40;
    }

    .hotel-guests-popover[hidden] {
        display: none;
    }

    .hotel-guests-row {
        align-items: center;
        display: flex;
        justify-content: space-between;
        min-height: 54px;
    }

    .hotel-guests-row+ .hotel-guests-row {
        border-top: 1px solid #edf1f5;
    }

    .hotel-guests-row>span {
        color: #1d2939;
        font-size: 14px;
        font-weight: 750;
    }

    .hotel-guests-stepper {
        align-items: center;
        display: grid;
        grid-template-columns: 32px 42px 32px;
        gap: 4px;
    }

    .hotel-guests-stepper button {
        align-items: center;
        background: #fff;
        border: 1px solid #f15d30;
        border-radius: 50%;
        color: #e24f25;
        display: inline-flex;
        height: 32px;
        justify-content: center;
        padding: 0;
        width: 32px;
    }

    .hotel-guests-stepper input {
        color: #101828;
        font-size: 15px;
        font-weight: 800;
        text-align: center;
        width: 42px;
    }

    .hotel-connect-search__submit {
        align-items: center;
        background: #f15d30;
        border: 0;
        border-radius: 0 5px 5px 0;
        color: #fff;
        cursor: pointer;
        display: inline-flex;
        font-size: 14px;
        font-weight: 800;
        gap: 9px;
        justify-content: center;
        min-width: 0;
        padding: 0 18px;
    }

    .hotel-connect-search__submit:hover {
        background: #d94820;
    }

    .hotel-search-errors {
        align-items: center;
        display: flex;
        font-size: 13px;
        gap: 7px;
        margin: 10px 2px 0;
    }


    .hotel-search-errors {
        color: #b42318;
        font-weight: 700;
    }

    .hotel-list-section .row {
        margin-left: -14px;
        margin-right: -14px;
    }

    .hotel-list-section .row>[class*="col-"] {
        padding-left: 14px;
        padding-right: 14px;
    }

    .hotels-count-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0 12px;
        margin-bottom: 18px;
        border-bottom: 2px solid var(--bg-light);
    }

    .hotels-count-bar .count-text {
        font-size: 14px;
        color: var(--text-muted);
    }

    .hotels-count-bar .count-text strong {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .hotel-results-layout {
        align-items: start;
        display: grid;
        gap: 24px;
        grid-template-columns: 280px minmax(0, 1fr);
    }

    .hotel-results-main {
        min-width: 0;
    }

    .hotel-filter-toggle {
        display: none;
    }

    .hotel-filter-panel {
        background: #fff;
        border: 1px solid #dfe6ed;
        border-radius: 8px;
        overflow: hidden;
        position: sticky;
        top: 18px;
    }

    .hotel-filter-panel__header {
        align-items: flex-start;
        display: flex;
        justify-content: space-between;
        padding: 18px;
    }

    .hotel-filter-panel__header h2 {
        color: #101828;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.25;
        margin: 3px 0 0;
    }

    .hotel-filter-panel__header a {
        color: #e24f25;
        font-size: 12px;
        font-weight: 750;
        margin-top: 3px;
    }

    .hotel-filter-panel__eyebrow {
        color: #667085;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .hotel-filter-group {
        border-top: 1px solid #e7ecf1;
        padding: 17px 18px;
    }

    .hotel-filter-group h3 {
        color: #1d2939;
        font-size: 14px;
        font-weight: 800;
        margin: 0 0 11px;
    }

    .hotel-filter-option {
        align-items: flex-start;
        color: #344054;
        cursor: pointer;
        display: flex;
        font-size: 13px;
        justify-content: space-between;
        line-height: 1.35;
        margin: 0;
        min-height: 31px;
        padding: 5px 0;
    }

    .hotel-filter-option>span:first-child {
        align-items: flex-start;
        display: flex;
        gap: 9px;
        min-width: 0;
    }

    .hotel-filter-option input {
        accent-color: #f15d30;
        flex: 0 0 auto;
        height: 17px;
        margin: 0;
        width: 17px;
    }

    .hotel-filter-option small {
        color: #98a2b3;
        font-size: 12px;
        margin-left: 10px;
    }

    .hotel-filter-option.is-disabled {
        cursor: default;
        opacity: .45;
    }

    .hotel-filter-stars,
    .hotel-card__stars {
        color: #f5a000;
        white-space: nowrap;
    }


    #hotel-filter-form.is-submitting {
        opacity: .6;
        pointer-events: none;
        transition: opacity .15s ease;
    }

    .hotel-active-filters {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: -3px 0 17px;
    }

    .hotel-active-filters>span {
        color: #667085;
        font-size: 12px;
        font-weight: 750;
    }

    .hotel-filter-chip {
        background: #fff4ef;
        border: 1px solid #ffd4c3;
        border-radius: 999px;
        color: #c8431d;
        font-size: 12px;
        font-weight: 750;
        padding: 5px 9px;
    }

    .hotel-card__meta {
        align-items: center;
        color: #667085;
        display: flex;
        font-size: 12px;
        font-weight: 700;
        gap: 8px;
        margin-bottom: 8px;
    }

    .hotel-list-section .hotel-card__img {
        height: 260px !important;
    }

    .hotel-list-section .hotel-card {
        height: auto !important;
    }

    .hotel-list-section .hotel-card__body {
        flex: 0 0 auto !important;
        padding-bottom: 12px !important;
    }

    .hotel-list-section .hotel-card__title {
        display: -webkit-box;
        min-height: 0;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .hotel-list-section .hotel-card__desc {
        display: -webkit-box;
        margin-bottom: 9px !important;
        min-height: 0;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .hotel-card__verified-amenities {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 9px;
    }

    .hotel-card__verified-amenities span {
        align-items: center;
        background: #f2f8f6;
        border: 1px solid #d8ebe5;
        border-radius: 6px;
        color: #256b5b;
        display: inline-flex;
        font-size: 11px;
        font-weight: 750;
        gap: 5px;
        line-height: 1.25;
        padding: 5px 7px;
    }

    .hotel-card__verified-amenities i {
        color: #159570;
    }

    .hotel-list-section .hotel-card__divider {
        margin-top: 1px;
        margin-bottom: 10px !important;
    }

    .hotel-list-section .hotel-card__footer {
        display: block;
        margin-top: 0 !important;
    }

    .hotel-list-section .hotel-card__btn {
        justify-content: center;
        width: 100%;
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

        .hotel-search-section>.container,
        .hotel-list-section>.container {
            width: min(100% - 24px, 1480px);
        }

        .hotel-search-section {
            padding-top: 22px !important;
        }

        .hotel-list-section {
            padding: 18px 0 40px !important;
        }

        .hotel-banner-search>.container {
            width: min(100% - 24px, 1480px) !important;
        }

        .hotel-connect-search {
            grid-template-columns: 1fr;
        }

        .hotel-connect-search__field {
            border-bottom: 1px solid #dfe6ed;
            border-right: 0;
        }

        .hotel-connect-search__submit {
            border-radius: 0 0 5px 5px;
            min-height: 50px;
        }

        .hotel-guests-popover {
            left: 8px;
            min-width: 0;
            right: 8px;
        }

        .hotel-page-hero .search-property-1>.row {
            grid-template-columns: 1fr;
        }

        .hotels-count-bar {
            align-items: flex-start;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }

    }

    @media (max-width: 1199px) {
        .hotel-connect-search {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .hotel-connect-search__field--destination,
        .hotel-connect-search__submit {
            grid-column: 1 / -1;
        }

        .hotel-connect-search__submit {
            border-radius: 0 0 5px 5px;
            min-height: 50px;
        }

        .hotel-page-hero>.container {
            display: block;
        }

        .hotel-hero-copy {
            margin-bottom: 18px;
        }

        .hotel-page-hero .search-wrap-modern {
            max-width: none;
        }
    }

    @media (max-width: 991px) {
        .hotel-list-section .hotel-card__img {
            height: 235px !important;
        }

        .hotel-results-layout {
            display: block;
        }

        .hotel-filter-toggle {
            align-items: center;
            background: #fff;
            border: 1px solid #dfe6ed;
            border-radius: 8px;
            color: #1d2939;
            display: flex;
            font-size: 14px;
            font-weight: 800;
            justify-content: space-between;
            margin-bottom: 14px;
            min-height: 46px;
            padding: 0 14px;
            width: 100%;
        }

        .hotel-filter-toggle>span {
            align-items: center;
            display: flex;
            gap: 8px;
        }

        .hotel-filter-toggle strong {
            align-items: center;
            background: #f15d30;
            border-radius: 50%;
            color: #fff;
            display: inline-flex;
            font-size: 11px;
            height: 22px;
            justify-content: center;
            margin-left: auto;
            margin-right: 10px;
            width: 22px;
        }

        .hotel-filter-toggle__arrow {
            transition: transform .2s ease;
        }

        .hotel-filter-toggle[aria-expanded="true"] .hotel-filter-toggle__arrow {
            transform: rotate(180deg);
        }

        .hotel-filter-panel {
            display: none;
            margin-bottom: 18px;
            position: static;
        }

        .hotel-filter-panel.is-open {
            display: block;
        }
    }

    @media (max-width: 767px) {
        .hotel-list-section .hotel-card__img {
            height: 225px !important;
        }

        body.hotel-filter-open .floating-contact {
            opacity: 0;
            pointer-events: none;
        }

        .hotel-connect-search {
            grid-template-columns: 1fr;
        }

        .hotel-connect-search__field--destination,
        .hotel-connect-search__submit {
            grid-column: auto;
        }
    }
</style>
@stop
@section('content')
{{-- Hero header --}}
<section class="hero-wrap hero-wrap-2 hotel-page-hero">
    <div class="container">
        <div class="row no-gutters slider-text justify-content-start">
            <div class="col-md-9 ftco-animate pb-5 text-left hotel-hero-copy">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i
                                class="fa fa-chevron-right"></i></a></span>
                    <span>Khách sạn <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Tìm nơi lưu trú phù hợp</h1>
            </div>
        </div>
    </div>
</section>

<section class="banner-search-section hotel-banner-search">
    <div class="container">
        <div class="search-wrap-modern ftco-animate fadeInUp ftco-animated">
            @include('page.common.searchHotel')
        </div>
    </div>
</section>

{{-- Hotel List --}}
<section class="hotel-list-section">
    <div class="container">

        <div class="hotels-count-bar">
            <div class="count-text">
                Tìm thấy <strong>{{ $hotels->total() }}</strong> nơi lưu trú phù hợp
            </div>
            <div style="font-size:13px;color:var(--text-muted);">
                <i class="fa fa-building" style="color:var(--primary);"></i>
                Sắp xếp theo: Mới cập nhật
            </div>
        </div>

        <div class="hotel-results-layout">
            <div>
                @include('page.common.hotelFilters')
            </div>

            <div class="hotel-results-main">
                @if(collect($selectedFilters)->flatten()->isNotEmpty())
                    <div class="hotel-active-filters">
                        <span>Đang lọc:</span>
                        @foreach($selectedFilters['types'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::ACCOMMODATION_TYPES[$value] }}</div>
                        @endforeach
                        @foreach($selectedFilters['stars'] as $value)
                            <div class="hotel-filter-chip">{{ $value }} sao</div>
                        @endforeach
                        @foreach($selectedFilters['amenities'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::AMENITIES[$value] }}</div>
                        @endforeach
                        @foreach($selectedFilters['room_facilities'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::ROOM_FACILITIES[$value] }}</div>
                        @endforeach
                        @foreach($selectedFilters['property_policies'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::PROPERTY_POLICIES[$value] }}</div>
                        @endforeach
                        @foreach($selectedFilters['meal_plans'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::MEAL_PLANS[$value] }}</div>
                        @endforeach
                        @foreach($selectedFilters['suitable_for'] as $value)
                            <div class="hotel-filter-chip">{{ \App\Models\Hotel::SUITABLE_FOR[$value] }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="row">
                    @if ($hotels->count())
                        @foreach($hotels as $hotel)
                            @include('page.common.itemHotel', ['hotel' => $hotel, 'hotelColumnClass' => 'col-sm-6 col-xl-4'])
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="fa fa-building-o"></i>
                                <h4>Không tìm thấy khách sạn nào</h4>
                                <p>Thử thay đổi điều kiện tìm kiếm hoặc bộ lọc để xem thêm kết quả.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="block-27">
                            {{ $hotels->links('page.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('hotel-connect-search');
    var trigger = document.getElementById('hotel-guests-trigger');
    var popover = document.getElementById('hotel-guests-popover');
    var summary = document.getElementById('hotel-guests-summary');
    var checkIn = document.getElementById('hotel-check-in');
    var checkOut = document.getElementById('hotel-check-out');
    var filterToggle = document.getElementById('hotel-filter-toggle');
    var filterPanel = document.getElementById('hotel-filter-panel');
    var filterForm = document.getElementById('hotel-filter-form');

    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', function () {
            var isOpen = filterPanel.classList.toggle('is-open');
            filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('hotel-filter-open', isOpen);
        });
    }

    if (filterForm) {
        filterForm.addEventListener('change', function (event) {
            if (!event.target.matches('input[type="checkbox"]')) {
                return;
            }

            filterForm.classList.add('is-submitting');
            filterForm.setAttribute('aria-busy', 'true');
            filterForm.submit();
        });
    }

    if (!form || !trigger || !popover || !summary) {
        return;
    }

    function inputValue(id, fallback) {
        var input = document.getElementById(id);
        var value = input ? parseInt(input.value, 10) : fallback;
        return Number.isFinite(value) ? value : fallback;
    }

    function updateGuestSummary() {
        summary.textContent = inputValue('hotel-adults', 2) + ' người lớn · '
            + inputValue('hotel-children', 0) + ' trẻ em · '
            + inputValue('hotel-rooms', 1) + ' phòng';
    }

    function setPopover(open) {
        popover.hidden = !open;
        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    trigger.addEventListener('click', function () {
        setPopover(popover.hidden);
    });

    form.querySelectorAll('.hotel-guests-stepper button').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.dataset.target);
            var step = parseInt(button.dataset.step, 10);
            var min = parseInt(input.min, 10);
            var max = parseInt(input.max, 10);
            var value = Math.max(min, Math.min(max, inputValue(input.id, min) + step));
            input.value = value;
            updateGuestSummary();
        });
    });

    form.querySelectorAll('.hotel-guests-stepper input').forEach(function (input) {
        input.addEventListener('change', updateGuestSummary);
    });

    document.addEventListener('click', function (event) {
        if (!popover.hidden && !popover.contains(event.target) && !trigger.contains(event.target)) {
            setPopover(false);
        }
    });

    if (checkIn && checkOut) {
        checkIn.addEventListener('change', function () {
            if (!checkIn.value) {
                return;
            }

            var nextDay = new Date(checkIn.value + 'T00:00:00');
            nextDay.setDate(nextDay.getDate() + 1);
            var minimum = nextDay.toISOString().slice(0, 10);
            checkOut.min = minimum;

            if (!checkOut.value || checkOut.value <= checkIn.value) {
                checkOut.value = minimum;
            }
        });
    }

    updateGuestSummary();
});
</script>
@stop
