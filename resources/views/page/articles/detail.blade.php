@extends('page.layouts.page')
@section('title', $article->a_title . ' | Miu Travel')
@section('style')
<style>
.article-detail-hero {
    background: #f8f9fc;
    padding: 42px 0 30px;
}
.article-detail-hero > .container,
.article-detail-body > .container {
    width: min(100% - 44px, 1480px);
    max-width: 1480px;
}
.article-detail-breadcrumb {
    color: #8792a2;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 18px;
}
.article-detail-breadcrumb a {
    color: #64748b;
    text-decoration: none;
}
.article-detail-breadcrumb a:hover {
    color: var(--primary, #f15d30);
}
.article-detail-title {
    color: #121826;
    font-size: clamp(2rem, 4vw, 4rem);
    font-weight: 900;
    line-height: 1.06;
    margin: 0 0 18px;
    letter-spacing: 0;
}
.article-detail-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px 18px;
    color: #697386;
    font-size: 15px;
    font-weight: 800;
}
.article-detail-meta i {
    color: var(--primary, #f15d30);
    margin-right: 5px;
}
.article-detail-body {
    background: #fff;
    padding: 34px 0 70px;
}
.article-detail-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 54px;
    align-items: start;
}
.article-detail-main {
    min-width: 0;
}
.article-lead {
    color: #334155;
    font-size: 1.08rem;
    line-height: 1.85;
    margin: 0 0 24px;
}
.article-main-image {
    display: block;
    width: 100%;
    min-height: 430px;
    border-radius: 8px;
    background-size: cover;
    background-position: center;
    cursor: zoom-in;
    box-shadow: 0 18px 48px rgba(15, 23, 42, .12);
}
.article-image-caption {
    color: #64748b;
    font-size: 14px;
    font-style: italic;
    line-height: 1.5;
    margin: 9px 0 0;
    text-align: center;
}
.article-rich-content {
    color: #243044;
    font-size: 1rem;
    line-height: 1.9;
}
.article-rich-content h2,
.article-rich-content h3 {
    color: #172033;
    font-weight: 900;
    line-height: 1.24;
    margin: 34px 0 14px;
}
.article-rich-content h2 { font-size: 1.75rem; }
.article-rich-content h3 { font-size: 1.36rem; }
.article-rich-content .news-heading-xl {
    color: #111827;
    font-size: clamp(2.1rem, 3.6vw, 3.1rem);
    font-weight: 900;
    line-height: 1.18;
    margin: 34px 0 16px;
}
.article-rich-content .news-heading-md {
    color: #111827;
    font-size: clamp(1.45rem, 2.2vw, 2rem);
    font-weight: 900;
    line-height: 1.25;
    margin: 28px 0 12px;
}
.article-rich-content .news-lead {
    color: #111827;
    font-size: 1.16rem;
    line-height: 1.85;
    font-weight: 650;
}
.article-rich-content .news-cta-link {
    color: var(--primary, #f15d30);
    display: inline-block;
    font-size: 1.12rem;
    font-weight: 900;
    margin: 12px 0;
    text-decoration: none;
}
.article-rich-content .news-cta-link:hover {
    color: #d94a1e;
    text-decoration: none;
}
.article-rich-content .news-inline-image {
    display: block;
    width: 100% !important;
    height: auto !important;
    border-radius: 18px;
    margin: 20px 0 8px;
    box-shadow: 0 14px 38px rgba(15, 23, 42, .1);
}
.article-rich-content .news-image-caption {
    color: #64748b;
    font-size: 14px;
    font-style: italic;
    line-height: 1.5;
    margin: 6px 0 22px;
    text-align: center;
}
.article-rich-content .news-quote {
    border-left: 5px solid var(--primary, #f15d30);
    background: #fff7ed;
    color: #334155;
    font-size: 1.05rem;
    font-style: italic;
    margin: 24px 0;
    padding: 16px 20px;
}
.article-rich-content .news-table {
    width: 100%;
    border-collapse: collapse;
}
.article-rich-content .news-table td,
.article-rich-content .news-table th {
    border: 1px solid #e2e8f0;
    padding: 10px 12px;
}
.article-rich-content p {
    color: #334155;
    font-size: 1rem;
    line-height: 1.9;
    margin-bottom: 18px;
}
.article-rich-content img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 18px 0 8px;
    cursor: zoom-in;
}
.article-gallery {
    margin: 30px 0 34px;
}
.article-gallery__title {
    color: #172033;
    font-size: 1.35rem;
    font-weight: 900;
    margin-bottom: 14px;
}
.article-gallery__grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
.article-gallery__item {
    display: block;
    min-height: 210px;
    border-radius: 8px;
    overflow: hidden;
    background-size: cover;
    background-position: center;
    cursor: zoom-in;
}
.article-sidebar {
    position: sticky;
    top: 92px;
}
.article-sidebar-box {
    border: 1px solid #e6edf4;
    border-radius: 8px;
    padding: 18px;
    margin-bottom: 18px;
    background: #fff;
}
.article-sidebar-box h3 {
    color: #172033;
    font-size: 1.02rem;
    font-weight: 900;
    margin: 0 0 14px;
}
.article-sidebar-search {
    position: relative;
}
.article-sidebar-search i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #94a3b8;
}
.article-sidebar-search input {
    width: 100%;
    height: 44px;
    border: 1px solid #dde5ee;
    border-radius: 6px;
    padding: 0 12px 0 40px;
    font-size: 14px;
}
.article-category-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.article-category-list li + li {
    border-top: 1px solid #edf1f5;
}
.article-category-list a {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    color: #334155;
    font-size: 14px;
    font-weight: 800;
    padding: 10px 0;
    text-decoration: none;
}
.article-category-list a:hover {
    color: var(--primary, #f15d30);
}
.article-recent-item {
    display: grid;
    grid-template-columns: 86px minmax(0, 1fr);
    gap: 12px;
    margin-bottom: 14px;
}
.article-recent-img {
    width: 86px;
    height: 70px;
    border-radius: 6px;
    background-size: cover;
    background-position: center;
}
.article-recent-title {
    color: #172033;
    display: block;
    font-size: 14px;
    font-weight: 900;
    line-height: 1.35;
    text-decoration: none;
}
.article-recent-title:hover {
    color: var(--primary, #f15d30);
}
.article-recent-date {
    color: #8792a2;
    display: block;
    font-size: 12px;
    font-weight: 700;
    margin-top: 5px;
}
@media (max-width: 991px) {
    .article-detail-layout {
        grid-template-columns: 1fr;
    }
    .article-sidebar {
        position: static;
    }
}
@media (max-width: 767px) {
    .article-detail-hero > .container,
    .article-detail-body > .container {
        width: min(100% - 24px, 1480px);
    }
    .article-main-image {
        min-height: 260px;
    }
    .article-gallery__grid {
        grid-template-columns: 1fr;
    }
}
</style>
@stop
@section('seo')
@stop
@section('content')
<section class="article-detail-hero">
    @php
        $articleCategory = $articleCategory ?? $article->category;
        $articleCategoryLabel = $articleCategory
            ? ($articleCategory->c_slug === 'dac-san' ? 'Đặc sản địa phương' : $articleCategory->c_name)
            : 'Tin tức';
        $articleCategoryUrl = ($articleCategory && $articleCategory->c_slug !== 'tin-tuc')
            ? route('articles.category', $articleCategory->c_slug)
            : route('articles.index');
    @endphp
    <div class="container">
        <div class="article-detail-breadcrumb">
            <a href="{{ route('page.home') }}">Trang chủ</a>
            <i class="fa fa-chevron-right mx-2"></i>
            <a href="{{ $articleCategoryUrl }}">{{ $articleCategoryLabel }}</a>
        </div>
        <h1 class="article-detail-title">{{ $article->a_title }}</h1>
        <div class="article-detail-meta">
            <span><i class="fa fa-user-o"></i>{{ optional($article->user)->name ?? 'Miu Travel' }}</span>
            <span><i class="fa fa-clock-o"></i>{{ optional($article->created_at)->format('H:i - d.m.Y') }}</span>
            <span><i class="fa fa-eye"></i>{{ number_format($article->a_view ?? 0) }} lượt xem</span>
        </div>
    </div>
</section>

<section class="article-detail-body">
    <div class="container">
        <div class="article-detail-layout">
            <article class="article-detail-main">
                <div class="article-rich-content" id="article-content">
                    {!! $article->a_content ?: $article->a_description !!}
                </div>

            </article>

            <aside class="article-sidebar">
                <div class="article-sidebar-box">
                    <form action="{{ $articleCategoryUrl }}" class="article-sidebar-search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="key_search" placeholder="Tìm kiếm...">
                    </form>
                </div>

                <div class="article-sidebar-box">
                    <h3>Danh mục</h3>
                    <ul class="article-category-list">
                        @foreach($categories as $categoryItem)
                            @php
                                $categoryUrl = $categoryItem->c_slug === 'tin-tuc'
                                    ? route('articles.index')
                                    : route('articles.category', $categoryItem->c_slug);
                            @endphp
                            <li>
                                <a href="{{ $categoryUrl }}">
                                    <span>{{ $categoryItem->c_slug === 'dac-san' ? 'Đặc sản địa phương' : $categoryItem->c_name }}</span>
                                    <span>{{ isset($categoryItem->news) ? $categoryItem->news->count() : 0 }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="article-sidebar-box">
                    <h3>Bài viết cùng danh mục</h3>
                    @if ($articles->count() > 0)
                        @foreach($articles as $recentArticle)
                            @php
                                $recentUrl = article_url($recentArticle);
                                $recentImage = $recentArticle->a_avatar ? asset(pare_url_file($recentArticle->a_avatar)) : asset('admin/dist/img/no-image.png');
                            @endphp
                            <div class="article-recent-item">
                                <a href="{{ $recentUrl }}" class="article-recent-img" style="background-image:url({{ $recentImage }});"></a>
                                <div>
                                    <a href="{{ $recentUrl }}" class="article-recent-title">{{ the_excerpt($recentArticle->a_title, 75) }}</a>
                                    <span class="article-recent-date">{{ optional($recentArticle->created_at)->format('H:i - d.m.Y') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
    $(function () {
        var $images = $('.article-detail-main img');

        if (!$images.length) {
            return;
        }

        var imageUrls = $images.map(function () {
            return $(this).attr('src');
        }).get().filter(Boolean);

        $images.each(function (index) {
            var $image = $(this);

            $image
                .addClass('js-public-gallery')
                .attr('data-public-index', index)
                .attr('data-public-title', '{{ e($article->a_title) }}')
                .data('public-images', imageUrls);
        });
    });
</script>
@stop
