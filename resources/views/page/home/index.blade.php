@extends('page.layouts.page')
@section('title', 'Cuộc đời là những chuyến đi | Miu Travel')
@section('style')
<style>
    :root {
        --home-soft: #eef3f8;
    }

    .hero-wrap>.container,
    .stats-section>.container,
    .ftco-select-destination>.container,
    #home-tours-section>.container,
    .ftco-about>.container,
    .articles-section>.container,
    .cta-modern>.container,
    .ftco-section.ftco-no-pb.ftco-no-pt>.container {
        width: min(100% - 28px, 1320px);
        max-width: 1320px;
    }

    #home-tours-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .home-section-kicker,
    .home-section-title {
        font-family: 'Times New Roman', Times, serif;
        font-weight: 800;
        line-height: 1.18;
        letter-spacing: 0;
    }

    .home-section-kicker {
        display: block;
        color: var(--primary, #f15d30);
        font-size: 2rem;
        margin-bottom: 4px;
    }

    .home-section-title {
        color: var(--text-dark, #1a1a2e);
        font-size: clamp(2.28rem, 2.8vw, 2.85rem);
    }

    .home-section-title--light {
        color: #fff;
    }

    @media (max-width: 767px) {
        .home-section-kicker {
            font-size: 1.5rem;
        }

        .home-section-title {
            font-size: 1.7rem;
        }
    }

    /* ── Hero enhancements ── */
    .hero-wrap {
        position: relative;
        overflow: hidden;
    }

    .home-hero {
        background-image: var(--home-hero-bg) !important;
        background-size: 100% calc(100% - 78px) !important;
        background-repeat: no-repeat !important;
        background-position: center bottom !important;
        background-attachment: fixed !important;
    }

    .hero-wrap.home-hero::before {
        display: none;
    }

    .home-hero::after {
        display: none !important;
    }

    .home-hero__shade {
        position: absolute;
        inset: 0;
        z-index: 0;
        background: linear-gradient(90deg, rgba(6, 22, 29, .56) 0%, rgba(6, 22, 29, .24) 38%, rgba(6, 22, 29, 0) 68%);
        pointer-events: none;
    }

    .home-hero__content {
        min-height: inherit;
        display: flex;
        align-items: center;
        padding: 46px 0 74px;
    }

    .home-hero__copy {
        max-width: 620px;
        color: #fff;
        text-align: left;
        transform: translateY(clamp(138px, 15vh, 210px));
    }

    .home-hero__copy .hero-badge {
        margin-bottom: 18px;
    }

    .home-hero__copy h1 {
        font-family: inherit;
        font-size: clamp(2.8rem, 5vw, 5.3rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: 0;
        color: #fff;
        text-shadow: 0 4px 30px rgba(0, 0, 0, .28);
        margin-bottom: 20px;
    }

    .home-hero__copy p {
        max-width: 560px;
        color: rgba(255, 255, 255, .9);
        font-size: 20px;
        line-height: 1.7;
        margin: 0 0 28px;
        text-shadow: 0 2px 18px rgba(0, 0, 0, .22);
    }

    /* Rich layered overlay */
    .hero-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(to right, rgba(5, 5, 15, .62) 0%, rgba(5, 5, 15, .34) 55%, rgba(5, 5, 15, .08) 100%),
            linear-gradient(to top, rgba(241, 93, 48, .1) 0%, transparent 50%);
        z-index: 0;
    }

    /* Decorative orb */
    .hero-wrap::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        top: -180px;
        right: -100px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(241, 93, 48, .12) 0%, transparent 70%);
        z-index: 0;
        pointer-events: none;
        animation: heroOrb 12s ease-in-out infinite alternate;
    }

    @keyframes heroOrb {
        from {
            transform: scale(1) translate(0, 0);
        }

        to {
            transform: scale(1.1) translate(20px, 30px);
        }
    }

    .hero-wrap .overlay {
        display: none;
    }

    .hero-wrap .container {
        position: relative;
        z-index: 1;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(8px);
        color: #fff;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 50px;
        margin-bottom: 20px;
    }

    .hero-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #f15d30;
        animation: pulse-dot 1.6s ease-in-out infinite;
    }

    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: .5;
            transform: scale(1.4);
        }
    }

    .hero-wrap h1 {
        font-family: 'Playfair Display', serif;
        font-size: 4.2rem;
        font-weight: 800;
        line-height: 1.12;
        color: #fff;
        letter-spacing: -.5px;
        text-shadow: 0 4px 32px rgba(0, 0, 0, .35);
        margin-bottom: 18px;
    }

    .hero-wrap h1 .h1-accent {
        background: linear-gradient(135deg, #ffa07a, #f15d30);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-wrap .hero-desc {
        font-size: 1.28rem;
        color: rgba(255, 255, 255, .78);
        max-width: 680px;
        line-height: 1.8;
        margin-bottom: 36px;
        font-weight: 400;
    }

    .hero-cta-group {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #f15d30, #e8431a);
        color: #fff;
        border: none;
        padding: 16px 36px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 17px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: transform 0.25s, box-shadow 0.25s;
        box-shadow: 0 8px 30px rgba(241, 93, 48, 0.4);
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 40px rgba(241, 93, 48, 0.55);
        color: #fff;
        text-decoration: none;
    }

    .btn-hero-video {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .btn-hero-video .play-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: background 0.25s, border-color 0.25s;
    }

    .btn-hero-video:hover {
        color: #f15d30;
        text-decoration: none;
    }

    .btn-hero-video:hover .play-circle {
        background: #f15d30;
        border-color: #f15d30;
    }

    .hero-stats {
        display: flex;
        gap: 30px;
        margin-top: 44px;
        padding-top: 32px;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        flex-wrap: wrap;
    }

    .hero-stat-item {
        text-align: left;
    }

    .hero-stat-num {
        font-size: 1.9rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
    }

    .hero-stat-num span {
        color: #f15d30;
    }

    .hero-stat-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.65);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 4px;
    }

    /* ── Search glass ── */
    .home-search-section {
        padding: 12px 0 12px !important;
        background: var(--home-soft);
    }

    .home-search-section>.container {
        width: min(100% - 44px, 1280px);
        max-width: 1280px;
    }

    .home-search-pill {
        width: min(100%, 1120px);
        margin: 0 auto;
    }

    .home-search-pill__form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, .82fr) 82px;
        align-items: stretch;
        min-height: 98px;
        padding: 14px 14px 14px 54px;
        margin: 0;
        border-radius: 999px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(15, 23, 42, .12);
    }

    .home-search-pill__field {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        padding-right: 42px;
        margin-right: 42px;
        border-right: 1px solid #e3e6eb;
    }

    .home-search-pill__label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #586166;
        font-size: 18px;
        font-weight: 850;
        line-height: 1.2;
        margin-bottom: 10px;
        white-space: nowrap;
    }

    .home-search-pill__label i {
        color: #4e5559;
        font-size: 23px;
    }

    .home-search-pill__control {
        position: relative;
        min-width: 0;
    }

    .home-search-pill__control::after {
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

    .home-search-pill__control input,
    .home-search-pill__control select {
        width: 100%;
        height: 34px;
        border: 0;
        outline: 0;
        color: #080808;
        background: transparent;
        font-size: 20px;
        font-weight: 500;
        line-height: 1.2;
        padding: 0 36px 0 0;
        appearance: none;
    }

    .home-search-pill__control input::placeholder {
        color: #080808;
        opacity: 1;
    }

    .home-search-pill__btn {
        width: 76px;
        height: 76px;
        align-self: center;
        justify-self: end;
        border: 0;
        border-radius: 50%;
        color: #fff;
        background: #ee5224;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .home-search-pill__btn:hover {
        background: #dc481d;
        transform: translateY(-1px);
    }

    /* ── Section header shared ── */
    .section-header {
        text-align: center;
        margin-bottom: 18px;
    }

    .section-header .sub {
        display: inline-block;
        color: #f15d30;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        position: relative;
        padding: 0 20px;
        margin-bottom: 12px;
    }

    .section-header .sub::before,
    .section-header .sub::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 30px;
        height: 2px;
        background: #f15d30;
        border-radius: 2px;
    }

    .section-header .sub::before {
        right: 100%;
        margin-right: -18px;
    }

    .section-header .sub::after {
        left: 100%;
        margin-left: -18px;
    }

    .section-header h2 {
        font-size: clamp(2.15rem, 2.6vw, 2.75rem);
        font-weight: 800;
        color: #1a1a2e;
        margin-bottom: 12px;
    }

    .section-header p {
        color: #888;
        max-width: 760px;
        margin: 0 auto;
        font-size: 17px;
        line-height: 1.7;
    }

    .home-services-section {
        background: var(--home-soft);
        padding: 24px 0 30px !important;
    }

    .home-services-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .home-service-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .home-service-item {
        min-height: 285px;
        position: relative;
        display: flex;
        align-items: flex-end;
        overflow: hidden;
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        background-size: cover;
        background-position: center;
        box-shadow: 0 16px 36px rgba(15, 23, 42, .14);
        transition: transform .24s ease, box-shadow .24s ease;
    }

    .home-service-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(8, 18, 30, .82) 0%, rgba(8, 18, 30, .42) 52%, rgba(8, 18, 30, .08) 100%);
    }

    .home-service-item:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-4px);
        box-shadow: 0 22px 46px rgba(15, 23, 42, .2);
    }

    .home-service-body {
        position: relative;
        z-index: 1;
        padding: 22px;
    }

    .home-service-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f15d30;
        color: #fff;
        font-size: 18px;
        margin-bottom: 14px;
        box-shadow: 0 10px 22px rgba(241, 93, 48, .28);
    }

    .home-service-body h3 {
        color: #fff;
        font-size: 1.25rem;
        font-weight: 850;
        line-height: 1.22;
        margin: 0 0 8px;
    }

    .home-service-body p {
        color: rgba(255, 255, 255, .86);
        font-size: 14px;
        line-height: 1.55;
        margin: 0 0 14px;
    }

    .home-service-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #fff;
        font-size: 13px;
        font-weight: 800;
    }

    @media (max-width: 991px) {
        .home-service-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575px) {
        .home-services-section>.container {
            width: min(100% - 24px, 1480px);
        }

        .home-service-grid {
            grid-template-columns: 1fr;
        }

        .home-service-item {
            min-height: 235px;
        }
    }

    /* ── Stats counter section ── */
    .stats-section {
        background: var(--miu-banner-bg, #123f55) !important;
        background-image: none !important;
        background-color: var(--miu-banner-bg, #123f55) !important;
        padding: 6px 0 10px;
        position: relative;
        overflow: hidden;
        border-top: 0;
        border-bottom: 0;
    }

    .stats-section::before {
        display: none;
    }

    .stat-box {
        text-align: center;
        padding: 6px 16px;
        position: relative;
    }

    .stat-box::after {
        display: none;
    }

    .stat-box:last-child::after {
        display: none;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        color: #fff;
        margin-bottom: 7px;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.3rem;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 4px;
        text-shadow: none;
    }

    .stat-number .plus {
        color: #f15d30;
    }

    .stat-label {
        font-size: 12px;
        color: rgba(255, 255, 255, .82);
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: none;
    }

    .stats-section--destination {
        margin: 0;
        border-radius: 0;
        box-shadow: none;
    }

    .stats-section--destination .row {
        margin-left: 0;
        margin-right: 0;
    }

    .stats-section--destination [class*="col-"] {
        padding-left: 0;
        padding-right: 0;
    }

    /* ── Destinations section ── */
    .ftco-select-destination>.container {
        width: min(100% - 28px, 1320px);
        max-width: 1320px;
    }

    .ftco-select-destination {
        padding: 24px 0 26px !important;
        background: var(--home-soft) !important;
        background-image: none !important;
        overflow: hidden;
    }

    .ftco-select-destination::before {
        content: none !important;
        display: none !important;
    }

    .ftco-select-destination>.container {
        width: 100% !important;
        max-width: none !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .destination-showcase {
        display: grid;
        grid-template-columns: minmax(82px, 7vw) minmax(280px, 24vw) minmax(210px, 17vw) minmax(460px, 1fr) minmax(82px, 7vw);
        grid-template-rows: 420px 360px;
        gap: 24px;
        align-items: stretch;
    }

    .destination-tile {
        position: relative;
        display: block;
        overflow: hidden;
        border-radius: 26px;
        background-size: cover;
        background-position: center;
        box-shadow: none;
        text-decoration: none;
        transition: transform .28s ease, box-shadow .28s ease;
    }

    .destination-tile:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 34px rgba(15, 24, 36, .16);
    }

    .destination-tile--edge-top {
        grid-column: 1;
        grid-row: 1;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-position: right center;
    }

    .destination-tile--top-main {
        grid-column: 2;
        grid-row: 1;
    }

    .destination-tile--top-side {
        grid-column: 3;
        grid-row: 1;
    }

    .destination-tile--bottom-edge {
        grid-column: 1;
        grid-row: 2;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-position: right center;
    }

    .destination-tile--bottom-wide {
        grid-column: 2;
        grid-row: 2;
    }

    .destination-tile--bottom-mid {
        grid-column: 3;
        grid-row: 2;
    }

    .destination-tile--bottom-right {
        grid-column: 4;
        grid-row: 2;
    }

    .destination-tile--right-peek {
        grid-column: 5;
        grid-row: 2;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        background-position: left center;
    }

    .destination-showcase__copy {
        grid-column: 4 / 6;
        grid-row: 1;
        align-self: start;
        padding: 18px min(4vw, 64px) 0 8px;
    }

    .destination-showcase__title {
        color: #111;
        font-family: 'Outfit', 'Inter', Arial, sans-serif;
        font-size: clamp(2.15rem, 3.15vw, 3.45rem);
        font-weight: 900;
        line-height: 1.08;
        margin: 0 0 18px;
        letter-spacing: 0;
    }

    .destination-showcase__desc {
        color: #625f5b;
        font-family: 'Outfit', 'Inter', Arial, sans-serif;
        font-size: 18px;
        font-weight: 550;
        line-height: 1.5;
        margin: 0 0 20px;
        max-width: 780px;
    }

    .destination-showcase__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-height: 58px;
        padding: 0 34px;
        border-radius: 28px;
        background: #f15d30;
        color: #fff;
        font-family: 'Outfit', 'Inter', Arial, sans-serif;
        font-size: 18px;
        font-weight: 850;
        text-decoration: none;
        box-shadow: 0 12px 28px rgba(241, 93, 48, .28);
        transition: transform .22s ease, box-shadow .22s ease;
    }

    .destination-showcase__btn:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 18px 36px rgba(241, 93, 48, .34);
    }

    .destination-showcase {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        grid-template-rows: 350px 310px;
        column-gap: 24px;
        row-gap: 24px;
        align-items: stretch;
    }

    .destination-showcase__copy {
        grid-column: 2;
        grid-row: 1;
        align-self: center;
    }

    .destination-rail {
        position: relative;
        overflow-x: auto;
        overflow-y: hidden;
        min-width: 0;
        cursor: grab;
        scrollbar-width: none;
        user-select: none;
        touch-action: pan-y;
    }

    .destination-rail::-webkit-scrollbar {
        display: none;
    }

    .destination-rail.is-dragging {
        cursor: grabbing;
    }

    .destination-rail.is-dragging .destination-rail__track {
        animation: none !important;
    }

    .destination-rail--top {
        grid-column: 1;
        grid-row: 1;
    }

    .destination-rail--bottom {
        grid-column: 1 / -1;
        grid-row: 2;
    }

    .destination-rail__track {
        display: flex;
        gap: 24px;
        width: max-content;
        height: 100%;
    }

    .destination-scroll-card {
        position: relative;
        flex: 0 0 clamp(265px, 22.5vw, 420px);
        height: 100%;
        overflow: hidden;
        border-radius: 26px;
        background-size: cover;
        background-position: center;
        color: #fff;
        text-decoration: none;
        -webkit-user-drag: none;
        box-shadow: 0 14px 32px rgba(15, 24, 36, .14);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .destination-scroll-card:nth-child(4n + 2) {
        flex-basis: clamp(240px, 19.5vw, 365px);
    }

    .destination-scroll-card:nth-child(4n + 3) {
        flex-basis: clamp(285px, 25.5vw, 470px);
    }

    .destination-scroll-card:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-4px);
        box-shadow: 0 20px 42px rgba(15, 24, 36, .2);
    }

    .destination-scroll-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, .7) 0%, rgba(0, 0, 0, .28) 48%, rgba(0, 0, 0, .04) 100%);
    }

    .destination-scroll-card__content {
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 18px;
        z-index: 1;
    }

    .destination-scroll-card__title {
        display: block;
        color: #fff;
        font-family: 'Playfair Display', serif;
        font-size: 1.02rem;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, .4);
    }

    .destination-scroll-card__badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        width: fit-content;
        padding: 5px 10px;
        border-radius: 999px;
        background: #f15d30;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 8px 18px rgba(241, 93, 48, .25);
    }

    .destination-card-list {
        width: min(100% - 28px, 1320px);
        max-width: 1320px;
        margin: 54px auto 0;
    }

    .destination-card-list__head {
        text-align: center;
        margin-bottom: 24px;
    }

    .destination-card-list__head .home-section-kicker {
        color: #f15d30;
        font-size: 1.8rem;
        margin-bottom: 4px;
    }

    .destination-card-list__head .home-section-title {
        color: #17172d;
        font-size: clamp(2rem, 2.45vw, 2.45rem);
        margin-bottom: 0 !important;
    }

    .destination-card-list .row {
        margin-left: -8px;
        margin-right: -8px;
    }

    .destination-card-list .row>[class*="col-"] {
        padding-left: 8px;
        padding-right: 8px;
        margin-bottom: 18px !important;
    }

    .destination-card-list .dest-card {
        height: 240px !important;
        border-radius: 12px !important;
        box-shadow: 0 12px 28px rgba(0, 0, 0, .18) !important;
        transition: transform .28s ease, box-shadow .28s ease;
    }

    .destination-card-list .dest-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 36px rgba(0, 0, 0, .24) !important;
    }

    .destination-card-list .dest-card>div {
        border-radius: 12px !important;
    }

    .destination-card-list .dest-card>div:last-child {
        padding: 18px 16px !important;
    }

    .destination-card-list .dest-card h3 {
        font-size: 1.08rem !important;
        line-height: 1.28 !important;
        margin-bottom: 10px !important;
    }

    .destination-card-list .dest-card span {
        font-size: 12px !important;
        padding: 5px 12px !important;
        border-radius: 999px !important;
        box-shadow: 0 8px 18px rgba(241, 93, 48, .24);
    }

    @media (max-width: 991px) {
        .destination-showcase {
            grid-template-columns: 1fr;
            grid-template-rows: auto;
            gap: 30px;
            padding: 0 18px;
        }

        .destination-showcase__copy {
            grid-column: 1;
            grid-row: 1;
            padding-top: 0;
        }

        .destination-rail--top,
        .destination-rail--bottom {
            grid-column: 1;
            height: 260px;
        }

        .destination-rail--top {
            grid-row: 2;
        }

        .destination-rail--bottom {
            grid-row: 3;
        }

        .destination-scroll-card {
            flex-basis: 300px;
            border-radius: 20px;
        }

        .destination-tile {
            min-height: 240px;
            grid-column: 1 !important;
            grid-row: auto !important;
            border-radius: 20px !important;
        }
    }

    @media (max-width: 767px) {

        .ftco-select-destination {
            padding-top: 22px !important;
            padding-bottom: 22px !important;
        }

        .destination-showcase__title {
            font-size: 2.2rem;
        }

        .destination-showcase__desc {
            font-size: 16px;
        }

        .destination-card-list {
            margin-top: 34px;
        }

        .destination-card-list .dest-card {
            height: 210px !important;
        }
    }

    /* ── About split ── */
    #home-tours-section {
        position: relative;
        overflow: hidden;
    }

    .home-why-section {
        position: relative;
        z-index: 1;
        overflow: hidden;
        background: var(--home-soft) !important;
        color: #0f1f33;
    }

    .home-why-section::before {
        content: none;
    }

    .home-why-section>.container {
        position: relative;
        z-index: 1;
    }

    .ftco-about>.container {
        width: min(100% - 28px, 1320px);
        max-width: 1320px;
    }

    .home-about-row {
        align-items: center !important;
        row-gap: 34px;
    }

    .home-about-visual {
        position: relative;
    }

    .home-about-visual .img {
        height: auto;
        min-height: 0;
        display: block !important;
        overflow: hidden;
        border-radius: 18px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .14);
        background: transparent !important;
        border: 1px solid #e8edf3 !important;
        margin-top: 0 !important;
    }

    .home-about-visual .img img {
        display: block;
        width: 100%;
        height: auto;
        object-fit: contain;
        border-radius: inherit;
        transition: opacity .22s ease;
    }

    .home-about-float {
        position: absolute;
        right: -18px;
        bottom: -18px;
        min-width: 120px;
        padding: 18px 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, #f97040, #f15d30);
        color: #fff;
        text-align: center;
        box-shadow: 0 12px 36px rgba(241, 93, 48, .35);
    }

    .home-about-float .num {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    .home-about-float .txt {
        font-size: 11.5px;
        font-weight: 700;
        line-height: 1.35;
        margin-top: 4px;
    }

    .home-about-copy {
        max-width: none;
        margin: 0;
    }

    .home-about-label {
        display: block;
        padding: 0;
        margin-bottom: 10px;
        border-radius: 0;
        border: 0;
        background: transparent;
        color: #f15d30;
        font-family: 'Outfit', 'Inter', Arial, sans-serif;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 2px;
        line-height: 1.18;
        text-transform: uppercase;
    }

    .home-about-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.15rem, 2.8vw, 2.75rem);
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1.25;
        margin: 0 0 16px;
    }

    .home-about-desc {
        color: #64748b;
        font-size: 18px;
        line-height: 1.8;
        margin-bottom: 26px;
    }

    .home-about-feature {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        width: 100%;
        padding: 18px 20px;
        margin-bottom: 10px;
        border-radius: 13px;
        background: #fff;
        border: 1.5px solid #f0ece8;
        color: inherit;
        cursor: pointer;
        text-align: left;
        font-family: inherit;
        transition: border-color .22s, box-shadow .22s, transform .22s;
    }

    button.home-about-feature {
        appearance: none;
    }

    .home-why-section .home-about-title {
        color: #0f1f33;
        font-size: clamp(2rem, 2.85vw, 2.75rem);
        line-height: 1.08;
        margin-bottom: 14px;
        text-shadow: none;
        white-space: nowrap;
    }

    .home-why-section .home-about-desc {
        color: #64748b;
        max-width: 620px;
        font-size: 16px;
        line-height: 1.65;
        margin-bottom: 18px;
    }

    .home-why-section .home-about-feature {
        background: #fff;
        border-color: #e8edf3;
        box-shadow: 0 12px 34px rgba(15, 23, 42, .06);
        max-width: 610px;
    }

    .home-why-section .home-about-feature:hover {
        border-color: rgba(241, 93, 48, .45);
        box-shadow: 0 16px 38px rgba(15, 23, 42, .1);
    }

    .home-why-section .home-about-feature.is-active {
        background: #fff7f3;
        border-color: rgba(241, 93, 48, .55);
        box-shadow: 0 16px 34px rgba(241, 93, 48, .12);
    }

    .home-why-section .home-about-feature:focus {
        outline: 0;
        border-color: rgba(241, 93, 48, .9);
        box-shadow: 0 0 0 4px rgba(241, 93, 48, .18);
    }

    .home-why-section .home-about-feature h5 {
        color: #0f1f33;
    }

    .home-why-section .home-about-feature p {
        color: #64748b;
    }

    .home-about-feature:hover {
        border-color: rgba(241, 93, 48, .25);
        box-shadow: 0 4px 20px rgba(241, 93, 48, .08);
        transform: translateX(4px);
    }

    .home-about-feature__icon {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
    }

    .home-about-feature h5 {
        color: #1a1a1a;
        font-size: 16.5px;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .home-about-feature p {
        color: #64748b;
        font-size: 14.5px;
        line-height: 1.5;
        margin: 0;
    }

    .home-about-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 15px 34px;
        margin-top: 18px;
        border-radius: 13px;
        background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%);
        color: #fff;
        font-size: 16.5px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 6px 22px rgba(241, 93, 48, .3);
        transition: transform .22s, box-shadow .22s;
    }

    .home-about-cta:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 12px 36px rgba(241, 93, 48, .38);
    }

    @media (max-width: 991px) {
        .home-about-visual .img {
            height: auto;
        }

        .home-why-section .home-about-title {
            white-space: normal;
        }

        .home-about-float {
            right: 16px;
            bottom: -16px;
        }
    }

    @media (max-width: 767px) {
        .home-why-section {
            padding-top: 22px !important;
            padding-bottom: 26px !important;
        }

        .home-about-visual .img {
            height: auto;
        }

        .home-about-title {
            font-size: 1.85rem;
        }

        .home-about-desc {
            font-size: 15.5px;
            line-height: 1.65;
        }

        .home-about-feature {
            padding: 16px;
            gap: 14px;
        }

        .home-about-feature__icon {
            width: 44px;
            height: 44px;
            font-size: 18px;
        }

        .home-about-float {
            min-width: 104px;
            padding: 14px 16px;
        }
    }

    /* ── View all ── */
    .section-viewall {
        text-align: center;
        margin-top: 14px;
    }

    .btn-viewall {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 2px solid #f15d30;
        color: #f15d30;
        border-radius: 50px;
        padding: 11px 28px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.25s;
    }

    .btn-viewall:hover {
        background: #f15d30;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(241, 93, 48, 0.35);
    }

    /* ── Modern CTA ── */
    .cta-modern {
        background: var(--home-soft);
        padding: 20px 0 36px;
        position: relative;
        overflow: hidden;
        text-align: left;
    }

    .cta-modern::before {
        display: none;
    }

    .cta-modern::after {
        display: none;
    }

    .cta-modern__inner {
        position: relative;
        z-index: 1;
        max-width: 1120px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: minmax(0, .82fr) minmax(360px, .58fr);
        gap: 24px;
        align-items: center;
        padding: 30px;
        border: 1px solid #f5cfc1;
        border-radius: 16px;
        background:
            linear-gradient(115deg, rgba(255, 247, 243, .96) 0%, rgba(255, 255, 255, .98) 48%, rgba(239, 253, 246, .9) 100%);
        box-shadow: 0 18px 42px rgba(15, 23, 42, .075);
    }

    .cta-modern__inner::before {
        content: none;
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background:
            linear-gradient(90deg, rgba(241, 93, 48, .16), transparent 42%),
            linear-gradient(180deg, rgba(255, 255, 255, .72), transparent 68%);
        opacity: .65;
        pointer-events: none;
    }

    .cta-modern__copy {
        position: relative;
        z-index: 1;
        min-width: 0;
    }

    .cta-modern__tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(241, 93, 48, .15);
        border: 1px solid rgba(241, 93, 48, .3);
        color: #f15d30;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 7px 18px;
        border-radius: 50px;
        margin-bottom: 14px;
    }

    .cta-modern__tag .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f15d30;
        animation: pulse-dot 1.6s ease-in-out infinite;
    }

    .cta-modern h2 {
        font-size: clamp(2rem, 2.7vw, 2.8rem);
        font-weight: 900;
        color: #102032;
        line-height: 1.08;
        max-width: 620px;
        margin-left: 0;
        margin-right: 0;
        margin-bottom: 12px;
    }

    .cta-modern p {
        color: #66758a;
        font-size: 16px;
        max-width: 610px;
        margin: 0 0 18px;
        line-height: 1.65;
    }

    .cta-modern__btns {
        display: flex;
        justify-content: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }

    .cta-modern__highlights {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        max-width: 600px;
        margin-top: 18px;
    }

    .cta-modern__highlight {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        padding: 10px 12px;
        border: 1px solid rgba(15, 32, 50, .08);
        border-radius: 10px;
        background: rgba(255, 255, 255, .72);
        color: #314154;
        font-size: 13px;
        font-weight: 750;
        line-height: 1.25;
    }

    .cta-modern__highlight i {
        flex: 0 0 auto;
        color: #f15d30;
        font-size: 15px;
    }

    .cta-modern__planner {
        position: relative;
        z-index: 1;
        padding: 18px;
        border: 1px solid #e9edf3;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .1);
    }

    .cta-modern__planner-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        color: #102032;
        font-size: 17px;
        font-weight: 900;
    }

    .cta-modern__planner-title i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #fff4ef;
        color: #f15d30;
    }

    .cta-modern__form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin: 0;
    }

    .cta-modern__field {
        min-width: 0;
    }

    .cta-modern__field--wide {
        grid-column: 1 / -1;
    }

    .cta-modern__field label {
        display: block;
        margin: 0 0 6px;
        color: #6b7788;
        font-size: 12px;
        font-weight: 800;
    }

    .cta-modern__control {
        position: relative;
    }

    .cta-modern__control i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa5b4;
        font-size: 14px;
        pointer-events: none;
    }

    .cta-modern__control input,
    .cta-modern__control select {
        width: 100%;
        height: 46px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #102032;
        font-size: 14px;
        font-weight: 650;
        outline: 0;
        padding: 0 12px 0 38px;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }

    .cta-modern__control select {
        appearance: none;
        padding-right: 30px;
    }

    .cta-modern__control--select::after {
        content: "\f107";
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #98a2b3;
        font-family: "FontAwesome";
        pointer-events: none;
    }

    .cta-modern__control input:focus,
    .cta-modern__control select:focus {
        border-color: rgba(241, 93, 48, .58);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(241, 93, 48, .1);
    }

    .cta-modern__actions {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 10px;
        align-items: stretch;
        margin-top: 2px;
    }

    .cta-modern__submit {
        width: 100%;
        border: 0;
        cursor: pointer;
    }

    .cta-modern__btn-primary {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #f15d30, #e04422);
        color: #fff;
        min-height: 50px;
        padding: 13px 24px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 15.5px;
        text-decoration: none;
        box-shadow: 0 10px 24px rgba(241, 93, 48, .3);
        transition: all .25s;
    }

    .cta-modern__btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(241, 93, 48, .55);
        color: #fff;
        text-decoration: none;
    }

    .cta-modern__btn-outline {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        border: 1px solid #dfe6ee;
        background: #fff;
        color: #102032;
        min-height: 50px;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 750;
        font-size: 15px;
        text-decoration: none;
        transition: all .25s;
    }

    .cta-modern__btn-outline:hover {
        border-color: #f15d30;
        color: #f15d30;
        background: #fff7f3;
        text-decoration: none;
    }

    @media (max-width: 991px) {
        .cta-modern__inner {
            grid-template-columns: 1fr;
            max-width: 760px;
        }

        .cta-modern h2,
        .cta-modern p {
            max-width: none;
        }
    }

    @media (max-width: 767px) {
        .cta-modern {
            padding: 14px 0 28px;
        }

        .cta-modern__inner {
            padding: 18px;
            border-radius: 14px;
        }

        .cta-modern h2 {
            font-size: 2rem;
        }

        .cta-modern p {
            font-size: 15.5px;
            margin-bottom: 18px;
        }

        .cta-modern__highlights,
        .cta-modern__form,
        .cta-modern__actions {
            grid-template-columns: 1fr;
        }

        .cta-modern__planner {
            padding: 15px;
        }

        .cta-modern__btn-outline {
            width: 100%;
        }
    }


    @keyframes scrollBounce {

        0%,
        100% {
            transform: translateX(-50%) translateY(0);
            opacity: .55;
        }

        50% {
            transform: translateX(-50%) translateY(8px);
            opacity: 1;
        }
    }

    /* ── Hero Responsive (override inline) ── */
    @media (max-width: 991px) {
        .hero-wrap h1 {
            font-size: clamp(1.8rem, 5vw, 3rem);
        }

        .hero-cta-group {
            flex-wrap: wrap;
        }

        .home-hero__content {
            padding: 36px 0 56px;
        }

        .home-hero__copy {
            max-width: 560px;
            transform: translateY(96px);
        }

        .home-search-pill__form {
            grid-template-columns: 1fr 1fr 70px;
            min-height: 88px;
            padding-left: 28px;
        }

        .home-search-pill__field {
            padding-right: 24px;
            margin-right: 24px;
        }

        .home-search-pill__label {
            font-size: 16px;
        }

        .home-search-pill__control input,
        .home-search-pill__control select {
            font-size: 17px;
        }

        .home-search-pill__btn {
            width: 64px;
            height: 64px;
            font-size: 23px;
        }
    }

    @media (max-width: 767px) {
        .hero-wrap h1 {
            font-size: clamp(1.6rem, 5.5vw, 2.2rem);
        }

        .hero-wrap .hero-desc {
            font-size: 0.95rem;
            margin-bottom: 20px;
            max-width: 100%;
        }

        .hero-stats {
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 24px;
            padding-top: 18px;
        }

        .hero-stat-num {
            font-size: 1.5rem;
        }

        .hero-stat-label {
            font-size: 10px;
        }

        .hero-badge {
            font-size: 11px;
            padding: 5px 13px;
            margin-bottom: 14px;
        }

        .home-hero__content {
            align-items: flex-end;
            padding: 32px 0 42px;
        }

        .home-hero__copy {
            transform: none;
        }

        .home-hero__copy h1 {
            font-size: clamp(2rem, 9vw, 3rem);
        }

        .home-hero__copy p {
            font-size: 16px;
            line-height: 1.55;
        }

        .home-search-section>.container {
            width: min(100% - 24px, 1320px);
        }

        .home-search-section {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }

        .home-search-pill__form {
            grid-template-columns: 1fr;
            min-height: 0;
            padding: 18px;
            border-radius: 18px;
        }

        .home-search-pill__field {
            padding: 0 0 14px;
            margin: 0 0 14px;
            border-right: 0;
            border-bottom: 1px solid #e3e6eb;
        }

        .home-search-pill__btn {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            font-size: 18px;
        }
    }

    @media (max-width: 575px) {
        .hero-wrap h1 {
            font-size: clamp(1.4rem, 6vw, 1.9rem);
        }

        .btn-hero-primary {
            width: 100%;
            justify-content: center;
        }

        .btn-hero-video {
            width: 100%;
            justify-content: center;
        }

        .hero-stat-item {
            flex: 1 1 30%;
            min-width: 0;
        }

        .hero-stat-num {
            font-size: 1.2rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
        }
    }
</style>
@stop
@section('content')

{{-- ── Hero ── --}}
<div class="hero-wrap home-hero js-fullheight" style="--home-hero-bg: url({{ asset('page/images/background.jpg') }});">
    <div class="home-hero__shade"></div>
    <div class="container home-hero__content">
        <div class="home-hero__copy">
            <div class="hero-badge">
                <span class="dot"></span>
                Tour linh hoạt theo lịch của bạn
            </div>
            <h1>Đặt tour linh hoạt theo ngày bạn muốn</h1>
            <p>Chọn tour yêu thích, tự chọn ngày khởi hành dự kiến và để Miu Travel hỗ trợ xác nhận lịch trình phù hợp
                cho chuyến đi.</p>
            <div class="hero-cta-group">
                <a href="{{ route('tour') }}" class="btn-hero-primary">
                    <i class="fa fa-calendar-check-o"></i>
                    Chọn tour ngay
                </a>
                <a href="{{ route('contact.index') }}" class="btn-hero-video">
                    <span class="play-circle"><i class="fa fa-phone"></i></span>
                    Tư vấn lịch trình
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ── Search ── --}}
<section class="ftco-section ftco-no-pb ftco-no-pt home-search-section">
    <div class="container">
        <div class="home-search-pill">
            <form action="{{ route('tour') }}" method="GET" class="home-search-pill__form">
                <div class="home-search-pill__field">
                    <label class="home-search-pill__label" for="home-tour-key">
                        <i class="fa fa-map-marker"></i>
                        Chuyến du lịch
                    </label>
                    <div class="home-search-pill__control">
                        <input type="text" id="home-tour-key" name="key_tour" value="{{ request('key_tour') }}"
                            placeholder="Tìm kiếm tour bạn muốn đi" autocomplete="off">
                    </div>
                </div>
                <div class="home-search-pill__field">
                    <label class="home-search-pill__label" for="home-tour-location">
                        <i class="fa fa-map-marker"></i>
                        Địa điểm
                    </label>
                    <div class="home-search-pill__control">
                        <select id="home-tour-location" name="location_id">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->l_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="home-search-pill__btn" aria-label="Tìm kiếm tour">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</section>

{{-- ── Destinations ── --}}
<section class="ftco-section img ftco-select-destination" style="position:relative;">
    <div class="container" style="position:relative;z-index:1;">
        @php
            $destinationItems = $locations->values();
            $destinationTop = $destinationItems;
            $destinationBottom = $destinationItems;
            $destinationUrl = function ($location) {
                $slug = $location->l_slug ?: \Illuminate\Support\Str::slug($location->l_name);

                return \Illuminate\Support\Str::contains($slug, 'khach-san')
                    ? route('hotel', ['location_id' => $location->id])
                    : route('tour', ['location_id' => $location->id]);
            };
        @endphp
        <div class="destination-showcase">
            @if ($destinationItems->count() > 0)
                <div class="destination-rail destination-rail--top ftco-animate">
                    <div class="destination-rail__track">
                        @foreach($destinationTop->concat($destinationTop) as $location)
                            <a href="{{ $destinationUrl($location) }}" class="destination-scroll-card"
                                style="background-image:url({{ asset(pare_url_file($location->l_image, '')) }});">
                                <span class="destination-scroll-card__content">
                                    <span class="destination-scroll-card__title">{{ $location->l_name }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="destination-showcase__copy ftco-animate">
                <h2 class="destination-showcase__title">Chuyên các Tour Quảng Bình</h2>
                <p class="destination-showcase__desc">
                    Với chính sách đa dạng hóa sản phẩm du lịch Quảng Bình, phục vụ tận tình và luôn mong
                    muốn quý khách thực sự cảm nhận được sự khác biệt khi tham gia từng chuyến du lịch
                    Quảng Bình.
                </p>
                <p class="destination-showcase__desc">
                    Luôn luôn sáng tạo, luôn luôn đổi mới, phục vụ những sản phẩm mà quý khách mong
                    muốn đã trở thành tâm nguyện, cũng như phương châm của công ty chúng tôi.
                </p>
            </div>

            @if ($destinationItems->count() > 0)
                <div class="destination-rail destination-rail--bottom ftco-animate">
                    <div class="destination-rail__track">
                        @foreach($destinationBottom->concat($destinationBottom) as $location)
                            <a href="{{ $destinationUrl($location) }}" class="destination-scroll-card"
                                style="background-image:url({{ asset(pare_url_file($location->l_image, '')) }});">
                                <span class="destination-scroll-card__content">
                                    <span class="destination-scroll-card__title">{{ $location->l_name }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ── Stats ── --}}
<section class="stats-section stats-section--destination">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa fa-map-signs"></i></div>
                    <div class="stat-number">500<span class="plus">+</span></div>
                    <div class="stat-label">Tours hoàn thành</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa fa-smile-o"></i></div>
                    <div class="stat-number">10.000<span class="plus">+</span></div>
                    <div class="stat-label">Khách hài lòng</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa fa-globe"></i></div>
                    <div class="stat-number">50<span class="plus">+</span></div>
                    <div class="stat-label">Điểm đến</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa fa-trophy"></i></div>
                    <div class="stat-number">10<span class="plus">+</span></div>
                    <div class="stat-label">Năm kinh nghiệm</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Tours ── --}}
<section class="ftco-section" id="home-tours-section"
    style="background:var(--home-soft); padding: 24px 0 30px !important;">
    <div class="container">
        <div class="row justify-content-center pb-2">
            <div class="col-12 col-md-8 heading-section text-center ftco-animate">
                <span class="home-section-kicker">Tour nổi bật</span>
                <h2 class="home-section-title mb-2">Chọn tour trước, chọn ngày đi sau</h2>
                <p style="color:#6b7280;font-size:15px;">Mỗi tour hiển thị rõ thời gian, địa điểm và giá để bạn dễ chọn
                    lịch khởi hành mong muốn.</p>
            </div>
        </div>

        {{-- Tour grid: render server-side trang 1, các trang sau dùng AJAX --}}
        <div class="home-tour-grid-shell" id="home-tour-grid-shell">
            <div class="home-tour-loading" id="home-tour-loading" role="status" aria-live="polite">
                <span class="home-tour-loading__box">
                    <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
                    Đang tải tour...
                </span>
            </div>
            <div class="row" id="home-tour-grid" aria-busy="false">
                @if($tours->count() > 0)
                    @foreach($tours as $tour)
                        @include('page.common.itemTour', ['tour' => $tour, 'showTourIntro' => true])
                    @endforeach
                @else
                    <div class="col-12 text-center py-5" style="color:#94a3b8;">
                        <i class="fa fa-compass" style="font-size:3rem;opacity:.3;"></i>
                        <p class="mt-3">Chưa có tour nào.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Pagination UI --}}
        @if($totalPages > 1)
            <div class="home-tour-pagination" id="home-tour-pagination">
                <ul class="htp-list">
                    {{-- Prev --}}
                    <li class="htp-item htp-prev" id="htp-prev">
                        <button class="htp-btn" aria-label="Trang trước" disabled>
                            <i class="fa fa-chevron-left"></i>
                        </button>
                    </li>

                    {{-- Page numbers --}}
                    @for($p = 1; $p <= $totalPages; $p++)
                        <li class="htp-item" data-page="{{ $p }}">
                            <button class="htp-btn {{ $p === 1 ? 'htp-btn--active' : '' }}">{{ $p }}</button>
                        </li>
                    @endfor

                    {{-- Next --}}
                    <li class="htp-item htp-next" id="htp-next">
                        <button class="htp-btn" aria-label="Trang sau">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </div>
        @endif

        <div class="section-viewall" style="margin-top:10px;">
            <a href="{{ route('tour') }}" class="btn-viewall">
                <i class="fa fa-compass"></i> Xem tất cả tour
            </a>
        </div>
    </div>
</section>

{{-- ── Services ── --}}
<section class="ftco-section home-services-section">
    <div class="container">
        <div class="row justify-content-center pb-2">
            <div class="col-12 col-md-8 heading-section text-center ftco-animate">
                <span class="home-section-kicker">Dịch vụ</span>
                <h2 class="home-section-title mb-2">Một hành trình, bốn nhu cầu chính</h2>
                <p style="color:#6b7280;font-size:15px;">Miu Travel tách rõ từng nhóm dịch vụ để khách dễ chọn tour, nơi
                    nghỉ, phương tiện và kinh nghiệm trước chuyến đi.</p>
            </div>
        </div>

        <div class="home-service-grid">
            <a href="{{ route('tour') }}" class="home-service-item ftco-animate"
                style="background-image:url({{ asset('page/images/services-1.jpg') }});">
                <span class="home-service-body">
                    <span class="home-service-icon"><i class="fa fa-map-signs"></i></span>
                    <h3>Tour du lịch</h3>
                    <p>Chọn tour theo điểm đến và thời gian, sau đó tự chọn ngày khởi hành mong muốn.</p>
                    <span class="home-service-link">Xem chi tiết<i class="fa fa-angle-right"></i></span>
                </span>
            </a>

            <a href="{{ route('hotel') }}" class="home-service-item ftco-animate"
                style="background-image:url({{ asset('page/images/services-2.jpg') }});">
                <span class="home-service-body">
                    <span class="home-service-icon"><i class="fa fa-building"></i></span>
                    <h3>Khách sạn</h3>
                    <p>Gợi ý nơi lưu trú phù hợp với điểm đến và lịch trình.</p>
                    <span class="home-service-link">Xem chi tiết<i class="fa fa-angle-right"></i></span>
                </span>
            </a>

            <a href="{{ route('car.rental') }}" class="home-service-item ftco-animate"
                style="background-image:url({{ asset('page/images/services-3.jpg') }});">
                <span class="home-service-body">
                    <span class="home-service-icon"><i class="fa fa-car"></i></span>
                    <h3>Thuê xe</h3>
                    <p>Chủ động phương tiện theo số lượng khách, cung đường và thời gian sử dụng.</p>
                    <span class="home-service-link">Xem chi tiết<i class="fa fa-angle-right"></i></span>
                </span>
            </a>

            <a href="{{ route('articles.category', 'kinh-nghiem-du-lich') }}" class="home-service-item ftco-animate"
                style="background-image:url({{ asset('page/images/services-4.jpg') }});">
                <span class="home-service-body">
                    <span class="home-service-icon"><i class="fa fa-map-o"></i></span>
                    <h3>Kinh nghiệm du lịch</h3>
                    <p>Tham khảo gợi ý điểm đến, thời điểm nên đi và lưu ý trước khi khởi hành.</p>
                    <span class="home-service-link">Xem chi tiết<i class="fa fa-angle-right"></i></span>
                </span>
            </a>
        </div>
    </div>
</section>

{{-- ── About ── --}}
<section class="ftco-section ftco-about ftco-no-pt img home-why-section"
    style="padding-top: 24px !important; padding-bottom: 30px !important; background: var(--home-soft);">
    <div class="container">
        <div class="col-md-12 heading-section ftco-animate">
            <div class="row d-flex align-items-center home-about-row">
                <div class="col-lg-7 mb-5 mb-lg-0 home-about-visual">
                    <div class="img w-100">
                        <img id="home-why-image" src="{{ asset('page/images/about_1.jpg') }}"
                            alt="Miu Travel Quảng Bình Quảng Trị">
                    </div>
                </div>
                <div class="col-lg-5 pl-lg-4 home-about-content">
                    <div class="home-about-copy">
                        <h2 class="home-about-title">Tại sao chọn Miu Travel</h2>
                        <p class="home-about-desc">
                            Miu Travel đồng hành cùng bạn từ lúc lên lịch trình đến từng khoảnh khắc trên đường đi,
                            để mỗi chuyến du lịch không chỉ an toàn, thuận tiện mà còn đầy cảm xúc và dấu ấn riêng.
                        </p>

                        <button type="button" class="home-about-feature is-active"
                            data-feature-image="{{ asset('page/images/about_1.jpg') }}"
                            data-feature-alt="Miu Travel Quảng Bình Quảng Trị" aria-pressed="true">
                            <div class="home-about-feature__icon"
                                style="background:linear-gradient(135deg,#f15d30,#e04422);">
                                <i class="fa fa-shield"></i>
                            </div>
                            <div>
                                <h5>An tâm trên mọi hành trình</h5>
                                <p>Tour được chuẩn bị kỹ lưỡng, có bảo hiểm và phương án hỗ trợ rõ ràng cho khách hàng.
                                </p>
                            </div>
                        </button>
                        <button type="button" class="home-about-feature"
                            data-feature-image="{{ asset('page/images/about_2.jpg') }}"
                            data-feature-alt="Hướng dẫn viên địa phương đồng hành cùng du khách" aria-pressed="false">
                            <div class="home-about-feature__icon"
                                style="background:linear-gradient(135deg,#4776e6,#8e54e9);">
                                <i class="fa fa-user-circle-o"></i>
                            </div>
                            <div>
                                <h5>Người đồng hành am hiểu địa phương</h5>
                                <p>Đội ngũ HDV nhiệt tình, giàu kinh nghiệm và luôn biết cách làm chuyến đi thêm thú vị.
                                </p>
                            </div>
                        </button>
                        <button type="button" class="home-about-feature"
                            data-feature-image="{{ asset('page/images/about_3.jpg') }}"
                            data-feature-alt="Lịch trình du lịch Quảng Bình được sắp xếp hợp lý" aria-pressed="false">
                            <div class="home-about-feature__icon"
                                style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                                <i class="fa fa-tag"></i>
                            </div>
                            <div>
                                <h5>Lịch trình hợp lý, chi phí rõ ràng</h5>
                                <p>Tư vấn tour phù hợp ngân sách, minh bạch chi phí và có nhiều ưu đãi theo mùa.</p>
                            </div>
                        </button>
                        <button type="button" class="home-about-feature"
                            data-feature-image="{{ asset('page/images/about_4.jpg') }}"
                            data-feature-alt="Miu Travel luôn hỗ trợ du khách trong suốt chuyến đi"
                            aria-pressed="false">
                            <div class="home-about-feature__icon"
                                style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                                <i class="fa fa-headphones"></i>
                            </div>
                            <div>
                                <h5>Luôn sẵn sàng hỗ trợ</h5>
                                <p>Đội ngũ chăm sóc khách hàng theo sát trước, trong và sau chuyến đi.</p>
                            </div>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('page.common.listCommentHot', compact('comments'))

{{-- ── Articles ── --}}
<section class="ftco-section articles-section">
    <div class="container">
        <div class="articles-section-head ftco-animate">
            <div class="articles-section-copy">
                <span class="home-section-kicker">Cẩm nang du lịch</span>
                <h2 class="home-section-title mb-2">Tin tức cập nhật</h2>
                <p>Những thông tin hữu ích về du lịch, cẩm nang du lịch, kinh nghiệm du lịch,...</p>
            </div>
            <div class="articles-section-stats">
                <div class="articles-stat">
                    <span class="articles-stat__number">{{ $articles->count() }}+</span>
                    <span class="articles-stat__label">Bài viết</span>
                </div>
                <div class="articles-stat-divider"></div>
                <div class="articles-stat">
                    <span class="articles-stat__number"><i class="fa fa-map-o"></i></span>
                    <span class="articles-stat__label">Cẩm nang</span>
                </div>
                <div class="articles-stat-divider"></div>
                <a href="{{ route('articles.index') }}" class="articles-stat articles-stat--link">
                    <span class="articles-stat__number"><i class="fa fa-angle-right"></i></span>
                    <span class="articles-stat__label">Xem tất cả</span>
                </a>
            </div>
        </div>
        @if ($articles->count() > 0)
            @php
                $featuredArticle = $articles->first();
                $featuredArticleUrl = article_url($featuredArticle);
                $sideArticles = $articles->slice(1, 2);
            @endphp
            <div class="articles-showcase">
                <a href="{{ $featuredArticleUrl }}" class="article-featured-card">
                    <div class="article-featured-media">
                        <img src="{{ $featuredArticle->a_avatar ? asset(pare_url_file($featuredArticle->a_avatar)) : asset('admin/dist/img/no-image.png') }}"
                            alt="{{ $featuredArticle->a_title }}" loading="lazy">
                        <span class="article-featured-badge">Mới nhất</span>
                    </div>
                    <div class="article-featured-body">
                        <span class="article-date-line">
                            <i class="fa fa-calendar-o"></i>
                            {{ date('d/m/Y', strtotime($featuredArticle->created_at)) }}
                        </span>
                        <h3>{{ the_excerpt($featuredArticle->a_title, 95) }}</h3>
                        <p>{{ the_excerpt(strip_tags($featuredArticle->a_description ?? ''), 240) }}</p>
                        <span class="article-featured-cta">
                            Đọc bài viết <i class="fa fa-angle-right"></i>
                        </span>
                    </div>
                </a>

                <div class="articles-side-list">
                    @forelse($sideArticles as $article)
                        @php $articleUrl = article_url($article); @endphp
                        <a href="{{ $articleUrl }}" class="article-side-card">
                            <img src="{{ $article->a_avatar ? asset(pare_url_file($article->a_avatar)) : asset('admin/dist/img/no-image.png') }}"
                                alt="{{ $article->a_title }}" loading="lazy">
                            <div class="article-side-body">
                                <span class="article-date-line">
                                    <i class="fa fa-calendar-o"></i>
                                    {{ date('d/m/Y', strtotime($article->created_at)) }}
                                </span>
                                <h3>{{ the_excerpt($article->a_title, 76) }}</h3>
                                <p>{{ the_excerpt(strip_tags($article->a_description ?? ''), 190) }}</p>
                                <span class="article-side-cta">
                                    Đọc bài viết <i class="fa fa-angle-right"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="article-side-empty">
                            <i class="fa fa-newspaper-o"></i>
                            <span>Các bài viết tiếp theo đang được cập nhật.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="articles-empty">
                <i class="fa fa-newspaper-o"></i>
                <p>Chưa có bài viết nào.</p>
            </div>
        @endif
    </div>
</section>
<style>
    .articles-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        text-align: left;
        width: min(100%, 1180px);
        margin: 0 auto 22px;
    }

    .articles-section-copy {
        min-width: 0;
    }

    .articles-section-copy .home-section-kicker {
        display: none;
    }

    .articles-section-copy .home-section-title {
        color: #14532d;
        margin-bottom: 6px !important;
    }

    .articles-section-copy p {
        color: #6f7787;
        font-size: 15px;
        line-height: 1.7;
        margin: 0;
        max-width: 640px;
    }

    .articles-section-stats {
        display: inline-flex;
        align-items: center;
        gap: 20px;
        background: rgba(255, 255, 255, .9);
        border: 1px solid rgba(134, 239, 172, .55);
        border-radius: 50px;
        padding: 10px 28px;
        box-shadow: 0 12px 32px rgba(22, 101, 52, .08);
        flex: 0 0 auto;
    }

    .articles-stat {
        text-align: center;
        text-decoration: none;
    }

    .articles-stat__number {
        display: block;
        color: #166534;
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 2px;
    }

    .articles-stat__number i {
        font-size: 1.15rem;
        line-height: 1;
    }

    .articles-stat__label {
        display: block;
        color: #7a8292;
        font-size: 11px;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .articles-stat-divider {
        width: 1px;
        height: 32px;
        background: #bbf7d0;
    }

    .articles-stat--link:hover {
        text-decoration: none;
    }

    .articles-stat--link:hover .articles-stat__number,
    .articles-stat--link:hover .articles-stat__label {
        color: #f15d30;
    }

    .articles-showcase {
        width: min(100%, 1480px);
        margin: 0 auto;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(460px, 1fr);
        gap: 26px;
        align-items: stretch;
    }

    .article-featured-card,
    .article-side-card {
        background: #fff;
        border: 1px solid #d9fbe4;
        border-radius: 14px;
        box-shadow: 0 16px 42px rgba(22, 101, 52, .07);
        color: inherit;
        text-decoration: none;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .article-featured-card:hover,
    .article-side-card:hover {
        color: inherit;
        text-decoration: none;
        transform: translateY(-3px);
        border-color: rgba(34, 197, 94, .35);
        box-shadow: 0 22px 54px rgba(22, 101, 52, .11);
    }

    .article-featured-card {
        display: grid;
        grid-template-rows: 290px 1fr;
        min-height: 100%;
    }

    .article-featured-media {
        position: relative;
        overflow: hidden;
        background: #eef3f8;
    }

    .article-featured-media img,
    .article-side-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .28s ease;
    }

    .article-featured-card:hover .article-featured-media img,
    .article-side-card:hover img {
        transform: scale(1.04);
    }

    .article-featured-badge {
        position: absolute;
        left: 18px;
        top: 18px;
        background: #166534;
        color: #fff;
        border-radius: 999px;
        padding: 6px 13px;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .article-featured-body {
        padding: 20px 22px 22px;
    }

    .article-date-line {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #6f7787;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .article-date-line i {
        color: #f15d30;
    }

    .article-featured-body h3,
    .article-side-body h3 {
        color: #14532d;
        font-weight: 900;
        line-height: 1.28;
        letter-spacing: 0;
        margin: 0;
    }

    .article-featured-body h3 {
        font-size: 1.35rem;
        margin-bottom: 9px;
    }

    .article-featured-body p,
    .article-side-body p {
        color: #5f6b7a;
        line-height: 1.6;
        margin: 0;
    }

    .article-featured-body p {
        font-size: 14px;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-featured-cta {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #f15d30;
        font-size: 13px;
        font-weight: 900;
    }

    .articles-side-list {
        display: grid;
        gap: 12px;
    }

    .article-side-card {
        display: grid;
        grid-template-columns: 245px minmax(0, 1fr);
        min-height: 178px;
    }

    .article-side-card img {
        min-height: 178px;
    }

    .article-side-body {
        padding: 15px 17px;
        min-width: 0;
    }

    .article-side-body h3 {
        font-size: .98rem;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-side-body p {
        font-size: 12.8px;
        line-height: 1.52;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 9px;
    }

    .article-side-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #f15d30;
        font-size: 12.5px;
        font-weight: 900;
    }

    .article-side-empty {
        min-height: 142px;
        border: 1px dashed #bbf7d0;
        border-radius: 14px;
        color: #7a8292;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 18px;
        background: rgba(255, 255, 255, .62);
        text-align: center;
    }

    @media (max-width: 991px) {
        .articles-section-head {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            width: 100%;
        }

        .articles-section-stats {
            width: 100%;
            justify-content: center;
        }

        .articles-showcase {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 575px) {
        .articles-section-stats {
            gap: 14px;
            padding: 9px 18px;
        }

        .article-featured-card {
            grid-template-rows: 220px 1fr;
        }

        .article-side-card {
            grid-template-columns: 138px minmax(0, 1fr);
            min-height: 126px;
        }

        .article-side-card img {
            min-height: 126px;
        }

        .article-side-body {
            padding: 12px;
        }

        .article-side-body p {
            display: none;
        }

        .article-side-cta {
            font-size: 12px;
        }
    }

    .articles-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 48px 0;
        color: #94a3b8;
    }

    .articles-empty i {
        font-size: 3rem;
        opacity: .3;
        display: block;
        margin-bottom: 12px;
    }

    #home-tours-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .articles-section>.container {
        width: min(100% - 44px, 1480px);
        max-width: 1480px;
    }

    .articles-section {
        background: var(--home-soft) !important;
        padding-top: 24px !important;
        padding-bottom: 30px !important;
    }

    #home-tour-grid {
        margin-left: -7px;
        margin-right: -7px;
        margin-bottom: 0;
        position: relative;
        transition: opacity .18s ease;
    }

    .home-tour-grid-shell {
        position: relative;
    }

    .home-tour-loading {
        position: absolute;
        inset: 0;
        z-index: 4;
        display: none;
        align-items: center;
        justify-content: center;
        min-height: 220px;
        border-radius: 8px;
        background: rgba(248, 250, 252, .82);
        color: #334155;
        font-weight: 800;
    }

    .home-tour-loading__box {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
    }

    .home-tour-grid-shell.is-loading .home-tour-loading {
        display: flex;
    }

    .home-tour-grid-shell.is-loading #home-tour-grid {
        opacity: .35;
        pointer-events: none;
    }

    #home-tour-grid>[class*="col-"] {
        padding-left: 7px;
        padding-right: 7px;
        margin-bottom: 16px !important;
    }

    #home-tours-section .home-tour-pagination {
        margin-top: 2px;
        gap: 6px;
    }

    #home-tours-section .section-viewall {
        margin-top: 8px !important;
    }

    #home-tours-section {
        background: var(--home-soft) !important;
        padding-top: 24px !important;
        padding-bottom: 30px !important;
    }

    @media (max-width: 767px) {
        #home-tours-section>.container {
            width: min(100% - 24px, 1480px);
        }

        .articles-section>.container {
            width: min(100% - 24px, 1480px);
        }

        #home-tours-section .home-tour-pagination {
            margin-top: 2px;
        }
    }
</style>

{{-- ── CTA ── --}}
<section class="cta-modern">
    <div class="container">
        <div class="cta-modern__inner">
            <div class="cta-modern__copy">
                <div class="cta-modern__tag">
                    <span class="dot"></span>
                    Tour linh hoạt
                </div>
                <h2>Chọn tour và tự chọn ngày khởi hành</h2>
                <p>Bạn chỉ cần chọn hành trình phù hợp, nhập ngày đi mong muốn và Miu Travel sẽ liên hệ xác nhận lại
                    lịch trình trước khi chốt booking.</p>
                <div class="cta-modern__highlights" aria-label="Lợi ích đặt tour linh hoạt">
                    <div class="cta-modern__highlight">
                        <i class="fa fa-calendar-check-o"></i>
                        Ngày đi linh hoạt
                    </div>
                    <div class="cta-modern__highlight">
                        <i class="fa fa-map-signs"></i>
                        Lịch trình rõ ràng
                    </div>
                    <div class="cta-modern__highlight">
                        <i class="fa fa-headphones"></i>
                        Tư vấn trước khi chốt
                    </div>
                </div>
            </div>
            <div class="cta-modern__planner">
                <div class="cta-modern__planner-title">
                    <i class="fa fa-paper-plane-o"></i>
                    Lên lịch nhanh
                </div>
                <form action="{{ route('tour') }}" method="GET" class="cta-modern__form">
                    <div class="cta-modern__field cta-modern__field--wide">
                        <label for="cta-tour-key">Bạn muốn đi đâu?</label>
                        <div class="cta-modern__control">
                            <i class="fa fa-search"></i>
                            <input type="text" id="cta-tour-key" name="key_tour" value="{{ request('key_tour') }}"
                                placeholder="VD: Đà Lạt, Phú Quốc, tour biển..." autocomplete="off">
                        </div>
                    </div>
                    <div class="cta-modern__field">
                        <label for="cta-tour-location">Điểm đến</label>
                        <div class="cta-modern__control cta-modern__control--select">
                            <i class="fa fa-map-marker"></i>
                            <select id="cta-tour-location" name="location_id">
                                <option value="">Tất cả điểm đến</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->l_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="cta-modern__field">
                        <label for="cta-tour-date">Ngày dự kiến</label>
                        <div class="cta-modern__control">
                            <i class="fa fa-calendar"></i>
                            <input type="date" id="cta-tour-date" name="start_date" value="{{ request('start_date') }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="cta-modern__field cta-modern__field--wide">
                        <label for="cta-tour-guests">Số khách</label>
                        <div class="cta-modern__control cta-modern__control--select">
                            <i class="fa fa-users"></i>
                            <select id="cta-tour-guests" name="guests">
                                <option value="">Chưa xác định</option>
                                @for($guestCount = 1; $guestCount <= 10; $guestCount++)
                                    <option value="{{ $guestCount }}" {{ request('guests') == $guestCount ? 'selected' : '' }}>
                                        {{ $guestCount }} khách
                                    </option>
                                @endfor
                                <option value="11" {{ request('guests') == 11 ? 'selected' : '' }}>Trên 10 khách</option>
                            </select>
                        </div>
                    </div>
                    <div class="cta-modern__actions">
                        <button type="submit" class="cta-modern__btn-primary cta-modern__submit">
                            <i class="fa fa-compass"></i> Tìm tour phù hợp
                        </button>
                        <a href="{{ route('contact.index') }}" class="cta-modern__btn-outline">
                            <i class="fa fa-phone"></i> Tư vấn
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@stop
@section('script')
<script>
    (function () {
        var AJAX_URL = '{{ route("home.tours.ajax") }}';
        var totalPages = {{ $totalPages ?? 1 }};
        var currentPage = 1;

        var $grid = $('#home-tour-grid');
        var $gridShell = $('#home-tour-grid-shell');
        var $pagination = $('#home-tour-pagination');
        var $prev = $('#htp-prev button');
        var $next = $('#htp-next button');
        var isLoading = false;

        function setLoading(isActive) {
            isLoading = isActive;
            $gridShell.toggleClass('is-loading', isActive);
            $grid.attr('aria-busy', isActive ? 'true' : 'false');
            $pagination.find('.htp-btn').prop('disabled', isActive);

            if (!isActive) {
                $prev.prop('disabled', currentPage <= 1);
                $next.prop('disabled', currentPage >= totalPages);
            }
        }

        /* ── Gọi AJAX lấy tours theo trang ── */
        function loadPage(page) {
            if (isLoading || page < 1 || page > totalPages) return;

            setLoading(true);

            $.ajax({
                url: AJAX_URL,
                type: 'GET',
                data: { page: page },
                success: function (res) {
                    /* Render tours */
                    $grid.html(res.html || '<div class="col-12 text-center py-5" style="color:#94a3b8;"><i class="fa fa-compass" style="font-size:3rem;opacity:.3;"></i><p class="mt-3">Không có tour nào.</p></div>');

                    currentPage = res.currentPage;

                    /* Cập nhật active button */
                    $pagination.find('.htp-item[data-page]').each(function () {
                        var p = parseInt($(this).data('page'));
                        $(this).find('.htp-btn').toggleClass('htp-btn--active', p === currentPage);
                    });

                    /* Scroll lên đầu section */
                    $('html, body').animate({
                        scrollTop: $('#home-tours-section').offset().top - 80
                    }, 400);
                },
                error: function () {
                    $grid.html('<div class="col-12 text-center py-5" style="color:#b91c1c;"><i class="fa fa-exclamation-circle" style="font-size:2rem;"></i><p class="mt-3">Không tải được danh sách tour. Vui lòng thử lại.</p></div>');
                },
                complete: function () {
                    setLoading(false);
                }
            });
        }

        /* ── Gắn sự kiện ── */
        $(document).on('click', '.htp-item[data-page] .htp-btn', function () {
            var page = parseInt($(this).closest('.htp-item').data('page'));
            if (page !== currentPage) loadPage(page);
        });

        $prev.on('click', function () { loadPage(currentPage - 1); });
        $next.on('click', function () { loadPage(currentPage + 1); });

        /* Khởi tạo trạng thái nút */
        if ($prev.length) {
            $prev.prop('disabled', true);
            $next.prop('disabled', totalPages <= 1);
        }
    })();

    (function () {
        var rails = document.querySelectorAll('.destination-rail');
        if (!rails.length) return;

        rails.forEach(function (rail) {
            var track = rail.querySelector('.destination-rail__track');
            if (!track) return;

            var startX = 0;
            var startScroll = 0;
            var isDragging = false;
            var isHovering = false;
            var moved = false;
            var capturedPointerId = null;
            var direction = rail.classList.contains('destination-rail--bottom') ? -1 : 1;
            var speed = rail.classList.contains('destination-rail--bottom') ? .08 : .09;
            var lastTime = performance.now();

            function loopTranslate() {
                var halfWidth = Math.max(1, track.scrollWidth / 2);
                if (rail.classList.contains('destination-rail--bottom')) {
                    rail.scrollLeft = halfWidth;
                }
            }

            function normalizeScroll() {
                var halfWidth = Math.max(1, track.scrollWidth / 2);
                if (rail.scrollLeft >= halfWidth) {
                    rail.scrollLeft -= halfWidth;
                } else if (rail.scrollLeft <= 0) {
                    rail.scrollLeft += halfWidth;
                }
            }

            function animate(now) {
                var deltaTime = Math.min(32, now - lastTime);
                lastTime = now;

                if (!isHovering && !isDragging) {
                    rail.scrollLeft += direction * speed * deltaTime;
                    normalizeScroll();
                }

                requestAnimationFrame(animate);
            }

            loopTranslate();
            requestAnimationFrame(animate);
            window.addEventListener('resize', loopTranslate);

            rail.addEventListener('mouseenter', function () {
                isHovering = true;
            });

            rail.addEventListener('mouseleave', function () {
                if (!isDragging) {
                    isHovering = false;
                }
            });

            rail.addEventListener('pointerdown', function (event) {
                if (event.button !== undefined && event.button !== 0) return;

                isDragging = true;
                isHovering = true;
                moved = false;
                startX = event.clientX;
                startScroll = rail.scrollLeft;
                rail.classList.add('is-dragging');
            });

            rail.addEventListener('pointermove', function (event) {
                if (!isDragging) return;

                var delta = event.clientX - startX;
                if (Math.abs(delta) > 4) {
                    event.preventDefault();
                    moved = true;
                    if (capturedPointerId === null) {
                        capturedPointerId = event.pointerId;
                        rail.setPointerCapture(event.pointerId);
                    }
                }

                rail.scrollLeft = startScroll - delta;
                normalizeScroll();
            });

            rail.addEventListener('pointerup', finishDrag);
            rail.addEventListener('pointercancel', finishDrag);

            function finishDrag(event) {
                if (!isDragging) return;

                isDragging = false;
                isHovering = rail.matches(':hover');
                rail.classList.remove('is-dragging');

                if (moved) {
                    rail.dataset.dragged = '1';
                    window.setTimeout(function () {
                        delete rail.dataset.dragged;
                    }, 150);
                }

                if (event && capturedPointerId !== null && rail.hasPointerCapture && rail.hasPointerCapture(capturedPointerId)) {
                    rail.releasePointerCapture(capturedPointerId);
                }
                capturedPointerId = null;
            }

            rail.addEventListener('click', function (event) {
                if (rail.dataset.dragged === '1') {
                    event.preventDefault();
                    event.stopPropagation();
                    delete rail.dataset.dragged;
                }
            }, true);
        });
    })();

    (function () {
        var image = document.getElementById('home-why-image');
        var buttons = document.querySelectorAll('.home-why-section .home-about-feature[data-feature-image]');
        if (!image || !buttons.length) return;

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var nextImage = button.getAttribute('data-feature-image');
                var nextAlt = button.getAttribute('data-feature-alt') || image.alt;
                if (!nextImage || image.src === nextImage) return;

                buttons.forEach(function (item) {
                    item.classList.toggle('is-active', item === button);
                    item.setAttribute('aria-pressed', item === button ? 'true' : 'false');
                });

                image.style.opacity = '0';
                window.setTimeout(function () {
                    image.src = nextImage;
                    image.alt = nextAlt;
                    image.style.opacity = '1';
                }, 180);
            });
        });
    })();
</script>
@stop
