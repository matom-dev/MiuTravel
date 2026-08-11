{{-- Article Card item (used inside articles-grid) --}}
@php
    $articleUrl = article_url($article);
@endphp
<div class="article-card ftco-animate fadeInUp ftco-animated">
    {{-- Thumbnail --}}
    <a href="{{ $articleUrl }}"
       class="article-card__thumb">
        <img src="{{ asset(pare_url_file($article->a_avatar)) }}"
             alt="{{ $article->a_title }}"
             loading="lazy">
        <div class="article-card__overlay"></div>
        {{-- Date badge --}}
        <div class="article-card__date">
            <span class="article-card__date-day">{{ date('d', strtotime($article->created_at)) }}</span>
            <span class="article-card__date-mon">{{ date('M', strtotime($article->created_at)) }}</span>
            <span class="article-card__date-yr">{{ date('Y', strtotime($article->created_at)) }}</span>
        </div>
    </a>

    {{-- Body --}}
    <div class="article-card__body">
        <h3 class="article-card__title" title="{{ $article->a_title }}">
            <a href="{{ $articleUrl }}">
                {{ the_excerpt($article->a_title, 80) }}
            </a>
        </h3>
        <p class="article-card__desc">{!! the_excerpt($article->a_description ?? '', 110) !!}</p>
        <a href="{{ $articleUrl }}"
           class="article-card__btn">
            Xem thêm <i class="fa fa-arrow-right ml-1"></i>
        </a>
    </div>
</div>
