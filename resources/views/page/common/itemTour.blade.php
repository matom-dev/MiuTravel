@if($tour->is_publicly_visible)
<?php
    $salePrice = $tour->t_price_adults - ($tour->t_price_adults * $tour->t_sale / 100);
    $introText = trim(strip_tags($tour->t_content ?: $tour->t_description ?: ''));
    $durationText = $tour->duration_text;
    $tourLocation = $tour->location->l_name ?? 'Linh hoạt';
    $isBookable = $tour->is_bookable;
?>
<div class="{{ !isset($itemTour) ? 'col-sm-6 col-lg-3' : '' }} ftco-animate fadeInUp ftco-animated {{ isset($itemTour) ? $itemTour : '' }}" style="margin-bottom: 20px;">
    <div class="tour-card">
        {{-- Ảnh thumbnail --}}
        <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}"
           class="tour-card__img"
           style="background-image: url({{ $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png') }});">
            <div class="tour-card__img-overlay"></div>
        </a>

        {{-- Nội dung --}}
        <div class="tour-card__body">
            <div class="tour-card__status {{ $isBookable ? 'tour-card__status--open' : 'tour-card__status--paused' }}">
                <i class="{{ $tour->public_status_icon }}"></i>
                {{ $tour->status_label }}
            </div>

            {{-- Tên tour --}}
            <h3 class="tour-card__title">
                <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}"
                   title="{{ $tour->t_title }}">
                    {{ the_excerpt($tour->t_title, 80) }}
                </a>
            </h3>

            {{-- Giá --}}
            <div class="tour-card__price-line">
                <span class="tour-card__price-line-current">{{ number_format($salePrice, 0, ',', '.') }}đ</span>
                <span class="tour-card__price-line-unit">/người lớn</span>
                @if($tour->t_sale > 0)
                    <span class="tour-card__price-line-old">{{ number_format($tour->t_price_adults, 0, ',', '.') }}đ</span>
                @endif
            </div>

            @if(!empty($showTourIntro) && $introText !== '')
                <p class="tour-card__intro">{{ the_excerpt($introText, 105) }}</p>
            @endif

            {{-- Thông tin nổi bật --}}
            <div class="tour-card__meta">
                <div class="tour-card__meta-item">
                    <i class="fa fa-map-marker"></i>
                    <span>Địa điểm: {{ $tourLocation }}</span>
                </div>
                <div class="tour-card__meta-item">
                    <i class="fa fa-clock-o"></i>
                    <span>Thời gian: {{ $durationText }}</span>
                </div>
            </div>

            <div class="tour-card__actions">
                <a href="{{ route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}"
                   class="tour-card__btn tour-card__btn--activity">
                    <i class="fa fa-eye"></i> Xem chi tiết
                </a>

                @if($isBookable)
                    <a href="{{ route('book.tour', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]) }}"
                       class="tour-card__btn">
                        <i class="fa fa-calendar-check-o"></i> Đặt tour
                    </a>
                @else
                    <span class="tour-card__btn tour-card__btn--disabled">
                        <i class="fa fa-lock"></i> Tạm ngưng
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
