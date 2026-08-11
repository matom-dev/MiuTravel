@extends('page.layouts.page')
@php
    $categoryLabel = isset($category)
        ? ($category->c_slug === 'dac-san' ? 'Đặc sản địa phương' : $category->c_name)
        : 'Tin tức';
    $usesTwoColumnLayout = isset($category)
        && in_array($category->c_slug, ['dac-san', 'kinh-nghiem-du-lich', 'tin-tuc'], true);
@endphp
@section('title', isset($category) ? $categoryLabel . ' | Miu Travel' : 'Tin tức Du lịch - Thông tin Du lịch, Tin tức Du Lịch Việt Nam 2026')
@section('style')
<style>
.article-hero-news {
    background: #f8f9fc;
    padding: 34px 0 26px;
}
.article-hero-news > .container,
.article-banner-search > .container,
.article-tools-section > .container,
.article-list-section > .container {
    width: min(100% - 44px, 1480px);
    max-width: 1480px;
}
.article-feature-head {
    margin-bottom: 18px;
}
.article-feature-kicker {
    color: var(--primary, #f15d30);
    font-size: 14px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1.8px;
    margin-bottom: 4px;
}
.article-feature-title {
    color: #172033;
    font-size: clamp(2rem, 3vw, 3.2rem);
    font-weight: 900;
    line-height: 1.05;
    margin: 0;
}
.article-feature-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.35fr) minmax(320px, .9fr);
    gap: 18px;
}
.article-feature-main,
.article-feature-side {
    position: relative;
    min-height: 420px;
    border-radius: 8px;
    overflow: hidden;
    background-size: cover;
    background-position: center;
    color: #fff;
    text-decoration: none;
    box-shadow: 0 16px 38px rgba(15, 23, 42, .16);
}
.article-feature-main::before,
.article-feature-side::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15,23,42,.05) 25%, rgba(15,23,42,.82) 100%);
}
.article-feature-side {
    min-height: 201px;
    display: block;
}
.article-feature-stack {
    display: grid;
    gap: 18px;
}
.article-feature-stack--single .article-feature-side {
    min-height: 420px;
}
.article-feature-content {
    position: absolute;
    left: 24px;
    right: 24px;
    bottom: 22px;
    z-index: 1;
}
.article-feature-content h2,
.article-feature-content h3 {
    color: #fff;
    font-weight: 900;
    line-height: 1.16;
    margin: 0 0 10px;
}
.article-feature-content h2 { font-size: clamp(1.75rem, 2.6vw, 2.7rem); }
.article-feature-content h3 { font-size: 1.2rem; }
.article-meta-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 12px;
    color: rgba(255,255,255,.86);
    font-size: 13px;
    font-weight: 700;
}
.article-tools-section {
    background: #f8f9fc;
    padding: 0 0 28px;
}
.article-page-hero {
    min-height: auto !important;
    background: #123f55 !important;
    background-image: none !important;
    padding: 34px 0 30px !important;
}
.article-page-hero::before {
    background: none !important;
}
.article-page-hero > .container {
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
}
.article-page-hero .slider-text {
    min-height: 0 !important;
    align-items: flex-start !important;
    padding: 0 !important;
}
.article-page-hero .ftco-animate.pb-5 {
    padding-bottom: 0 !important;
}
.article-page-hero__copy {
    margin-bottom: 24px;
}
.article-page-hero .article-search-card {
    box-shadow: 0 18px 48px rgba(0, 0, 0, .18);
}
.article-page-hero--category > .container {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(360px, 520px);
    gap: 24px;
    align-items: end;
}
.article-page-hero--category .slider-text {
    margin: 0 !important;
}
.article-page-hero--category .article-page-hero__copy {
    flex: 0 0 100%;
    max-width: 100%;
    margin-bottom: 0;
}
.article-page-hero--category .article-search-card {
    justify-self: end;
    width: 100%;
    max-width: 520px;
    padding: 10px;
}
.article-page-hero--category .article-search-card form {
    gap: 8px;
}
.article-page-hero--category .article-search-input-wrap input,
.article-page-hero--category .article-search-btn {
    height: 42px;
    font-size: 14px;
}
.article-page-hero--category .article-search-input-wrap .search-icon {
    left: 14px;
}
.article-page-hero--category .article-search-input-wrap input {
    padding-left: 40px;
}
.article-page-hero--category .article-search-btn {
    padding: 0 14px;
}
.article-search-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 8px;
    padding: 14px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
}
.article-search-card form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto auto;
    gap: 10px;
    align-items: center;
}
.article-search-input-wrap {
    position: relative;
}
.article-search-input-wrap .search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}
.article-search-input-wrap input {
    width: 100%;
    height: 48px;
    border: 1px solid #dde5ee;
    border-radius: 6px;
    padding: 0 16px 0 44px;
    color: #172033;
    font-size: 15px;
    background: #fbfcfe;
    outline: none;
}
.article-search-input-wrap input:focus {
    border-color: var(--primary, #f15d30);
    box-shadow: 0 0 0 4px rgba(241,93,48,.08);
}
.article-search-btn {
    height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 0;
    border-radius: 6px;
    padding: 0 18px;
    color: #fff;
    background: var(--primary, #f15d30);
    font-size: 15px;
    font-weight: 900;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}
.article-search-btn--muted {
    background: #64748b;
}
.article-list-section {
    background: #fff;
    padding: 34px 0 64px !important;
}
.article-result-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    color: #64748b;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 22px;
}
.article-result-bar strong {
    color: var(--primary, #f15d30);
}
.article-feed {
    display: grid;
    gap: 22px;
}
.article-list-section--two-column .article-feed {
    gap: 28px;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
.article-feed-item {
    display: grid;
    grid-template-columns: 390px minmax(0, 1fr);
    gap: 28px;
    align-items: stretch;
    padding-bottom: 22px;
    border-bottom: 1px solid #edf1f5;
}
.article-list-section--two-column .article-feed-item {
    border-bottom: 0;
    gap: 20px;
    grid-template-columns: minmax(210px, .85fr) minmax(0, 1fr);
    padding-bottom: 0;
}
.article-list-section--two-column .article-feed-item__image {
    min-height: 205px;
}
.article-list-section--two-column .article-feed-item__title {
    font-size: clamp(1.18rem, 1.45vw, 1.55rem);
}
.article-list-section--two-column .article-feed-item__desc {
    -webkit-line-clamp: 2;
}
.article-feed-item__image {
    min-height: 218px;
    border-radius: 8px;
    overflow: hidden;
    background-size: cover;
    background-position: center;
    text-decoration: none;
}
.article-feed-item__content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}
.article-feed-item__source {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px 12px;
    color: #7c8798;
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 8px;
}
.article-feed-item__source i {
    color: var(--primary, #f15d30);
}
.article-feed-item__title {
    font-size: clamp(1.25rem, 1.6vw, 1.7rem);
    font-weight: 900;
    line-height: 1.24;
    margin: 0 0 10px;
    color: #172033;
}
.article-feed-item__title a {
    color: inherit;
    text-decoration: none;
}
.article-feed-item__title a:hover {
    color: var(--primary, #f15d30);
}
.article-feed-item__desc {
    color: #64748b;
    font-size: 15px;
    line-height: 1.65;
    margin: 0 0 14px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.article-feed-item__btn {
    width: fit-content;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary, #f15d30);
    border: 1px solid rgba(241,93,48,.38);
    border-radius: 999px;
    padding: 8px 15px;
    font-size: 13.5px;
    font-weight: 900;
    text-decoration: none;
}
.article-feed-item__btn:hover {
    color: #fff;
    background: var(--primary, #f15d30);
    text-decoration: none;
}
	.article-empty {
	    text-align: center;
	    padding: 70px 20px;
	    color: #94a3b8;
	    border: 1px dashed #dbe3ed;
	    border-radius: 8px;
	}
	@media (max-width: 991px) {
    .article-page-hero--category > .container {
        display: block;
    }
    .article-page-hero--category .article-page-hero__copy {
        margin-bottom: 18px;
    }
    .article-page-hero--category .article-search-card {
        max-width: none;
    }
    .article-feature-grid {
        grid-template-columns: 1fr;
    }
    .article-feature-main {
        min-height: 340px;
    }
    .article-feature-stack {
        grid-template-columns: 1fr 1fr;
    }
    .article-feature-stack--single {
        grid-template-columns: 1fr;
    }
	    .article-feed-item {
	        grid-template-columns: 300px minmax(0, 1fr);
	        gap: 18px;
	    }
        .article-list-section--two-column .article-feed {
            grid-template-columns: 1fr;
        }
        .article-list-section--two-column .article-feed-item {
            grid-template-columns: 300px minmax(0, 1fr);
        }
	}
	@media (max-width: 767px) {
    .article-hero-news > .container,
    .article-banner-search > .container,
    .article-tools-section > .container,
    .article-list-section > .container {
        width: min(100% - 24px, 1480px);
    }
	    .article-feature-stack,
	    .article-search-card form,
	    .article-feed-item {
	        grid-template-columns: 1fr;
	    }
        .article-list-section--two-column .article-feed-item {
            grid-template-columns: 1fr;
        }
    .article-feature-main,
    .article-feature-side {
        min-height: 270px;
    }
    .article-feed-item__image {
        min-height: 210px;
    }
    .article-result-bar {
        align-items: flex-start;
        flex-direction: column;
    }
	    .article-search-btn {
	        width: 100%;
	    }
	}
	</style>
	@stop
	@section('content')
	    <section class="hero-wrap hero-wrap-2 article-page-hero {{ isset($category) ? 'article-page-hero--category' : '' }}">
        <div class="container">
            <div class="row no-gutters slider-text justify-content-start">
                <div class="col-md-9 ftco-animate pb-5 text-left article-page-hero__copy">
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                        <span>{{ $categoryLabel }} <i class="fa fa-chevron-right"></i></span>
                    </p>
                    <h1 class="mb-0 bread">{{ $categoryLabel }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="banner-search-section article-banner-search">
        <div class="container">
            <div class="search-wrap-modern article-search-card">
                <div class="home-search-pill">
                    <form action="{{ isset($category) ? route('articles.category', $category->c_slug) : route('articles.index') }}" method="GET" id="article-search-form" class="home-search-pill__form home-search-pill__form--article">
                        <div class="home-search-pill__field">
                            <label class="home-search-pill__label" for="article-keyword">
                                <i class="fa fa-search"></i>
                                Từ khóa
                            </label>
                            <div class="home-search-pill__control">
                                <input
                                    type="text"
                                    id="article-keyword"
                                    name="key_search"
                                    value="{{ request('key_search') }}"
                                    placeholder="Nhập từ khóa tìm kiếm"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <button type="submit" class="home-search-pill__btn" aria-label="Tìm kiếm bài viết">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if(isset($featuredArticles) && $featuredArticles->count() > 0)
        <section class="article-hero-news">
            <div class="container">
                <div class="article-feature-grid">
                    @php
                        $mainArticle = $featuredArticles->first();
                        $mainUrl = article_url($mainArticle);
                        $mainImage = $mainArticle->a_avatar ? asset(pare_url_file($mainArticle->a_avatar)) : asset('admin/dist/img/no-image.png');
                    @endphp
                    <a href="{{ $mainUrl }}" class="article-feature-main" style="background-image:url({{ $mainImage }});">
                        <div class="article-feature-content">
                            <h2>{{ $mainArticle->a_title }}</h2>
                            <div class="article-meta-line">
                                <span>{{ optional($mainArticle->user)->name ?? 'Miu Travel' }}</span>
                                <span><i class="fa fa-clock-o"></i> {{ optional($mainArticle->created_at)->format('H:i - d.m.Y') }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="article-feature-stack {{ $featuredArticles->count() === 2 ? 'article-feature-stack--single' : '' }}">
                        @foreach($featuredArticles->skip(1) as $featuredArticle)
                            @php
                                $featuredUrl = article_url($featuredArticle);
                                $featuredImage = $featuredArticle->a_avatar ? asset(pare_url_file($featuredArticle->a_avatar)) : asset('admin/dist/img/no-image.png');
                            @endphp
                            <a href="{{ $featuredUrl }}" class="article-feature-side" style="background-image:url({{ $featuredImage }});">
                                <div class="article-feature-content">
                                    <h3>{{ $featuredArticle->a_title }}</h3>
                                    <div class="article-meta-line">
                                        <span>{{ optional($featuredArticle->user)->name ?? 'Miu Travel' }}</span>
                                        <span>{{ optional($featuredArticle->created_at)->format('H:i - d.m.Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="ftco-section article-list-section {{ $usesTwoColumnLayout ? 'article-list-section--two-column' : '' }}">
        <div class="container">
            <div class="article-result-bar">
                <div>
                    <strong>{{ $articles->firstItem() ?? 0 }} - {{ $articles->lastItem() ?? 0 }}</strong>
                    của <strong>{{ $articles->total() }}</strong> kết quả
                </div>
                @if(request('key_search'))
                    <div>Kết quả tìm kiếm cho: <strong>"{{ request('key_search') }}"</strong></div>
                @endif
            </div>

            @if ($articles->count() > 0)
                <div class="article-feed">
                    @foreach($articles as $article)
                        @php
                            $articleUrl = article_url($article);
                            $articleImage = $article->a_avatar ? asset(pare_url_file($article->a_avatar)) : asset('admin/dist/img/no-image.png');
                        @endphp
                        <article class="article-feed-item">
                            <a href="{{ $articleUrl }}"
                               class="article-feed-item__image"
                               style="background-image:url({{ $articleImage }});"
                               aria-label="{{ $article->a_title }}"></a>
                            <div class="article-feed-item__content">
                                <div class="article-feed-item__source">
                                    <span><i class="fa fa-user-o"></i> {{ optional($article->user)->name ?? 'Miu Travel' }}</span>
                                    <span><i class="fa fa-clock-o"></i> {{ optional($article->created_at)->format('H:i - d.m.Y') }}</span>
                                </div>
                                <h2 class="article-feed-item__title">
                                    <a href="{{ $articleUrl }}">{{ $article->a_title }}</a>
                                </h2>
                                <p class="article-feed-item__desc">{!! the_excerpt(strip_tags($article->a_description ?? ''), 230) !!}</p>
                                <a href="{{ $articleUrl }}" class="article-feed-item__btn">
                                    Đọc bài viết <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="article-empty">
                    <i class="fa fa-newspaper-o" style="font-size:3rem;color:#e2e8f0;display:block;margin-bottom:14px;"></i>
                    <p style="font-size:15px;margin:0;">Không tìm thấy bài viết nào.</p>
                </div>
            @endif

            @if($articles->hasPages())
                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="block-27">
                            {{ $articles->links('page.pagination.default') }}
                        </div>
                    </div>
                </div>
            @endif
	        </div>
	    </section>
	@stop
@section('script')
@stop
