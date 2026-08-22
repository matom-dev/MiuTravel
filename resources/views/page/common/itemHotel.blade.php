@php
    $isRelated = isset($itemHotel) && $itemHotel === 'item-related-tour';
    $hotelContext = array_filter(request()->only([
        'destination', 'check_in', 'check_out', 'adults', 'children', 'rooms',
        'types', 'stars', 'amenities', 'room_facilities', 'property_policies', 'meal_plans', 'suitable_for'
    ]), function ($value) {
        return $value !== null && $value !== '';
    });
    $hotelDetailUrl = route('hotel.detail', ['id' => $hotel->id, 'slug' => safeTitle($hotel->h_name)]);
    if ($hotelContext) {
        $hotelDetailUrl .= '?'.http_build_query($hotelContext);
    }
    $visibleHotelTags = [];
    foreach ([
        [\App\Models\Hotel::AMENITIES, $hotel->h_amenities ?? []],
        [\App\Models\Hotel::ROOM_FACILITIES, $hotel->h_room_facilities ?? []],
        [\App\Models\Hotel::PROPERTY_POLICIES, $hotel->h_property_policies ?? []],
        [\App\Models\Hotel::MEAL_PLANS, $hotel->h_meal_plans ?? []],
    ] as $tagGroup) {
        foreach ($tagGroup[1] as $tagKey) {
            if (isset($tagGroup[0][$tagKey])) {
                $visibleHotelTags[] = $tagGroup[0][$tagKey];
            }
        }
    }
    $visibleHotelTags = array_slice(array_values(array_unique($visibleHotelTags)), 0, 3);
@endphp

@if($isRelated)
    {{-- ── Compact card cho sidebar Liên Quan ── --}}
    <a href="{{ $hotelDetailUrl }}"
       class="related-hotel-item">
        <div class="related-hotel-item__img"
             style="background-image: url({{ $hotel->h_image ? asset(pare_url_file($hotel->h_image)) : asset('admin/dist/img/no-image.png') }});">
        </div>
        <div class="related-hotel-item__info">
            <h5 class="related-hotel-item__name">{{ the_excerpt($hotel->h_name, 45) }}</h5>
            @if($hotel->h_address)
                <p class="related-hotel-item__location">
                    <i class="fa fa-map-marker"></i> {{ the_excerpt($hotel->h_address, 55) }}
                </p>
            @endif
            <span class="related-hotel-item__contact">
                <i class="fa fa-phone"></i> Liên hệ trực tiếp
            </span>
        </div>
    </a>

@else
    {{-- ── Full card cho danh sách chính ── --}}
    <div class="{{ $hotelColumnClass ?? 'col-sm-6 col-lg-3' }} ftco-animate fadeInUp ftco-animated" style="margin-bottom: 20px;">
        <div class="hotel-card">

            <a href="{{ $hotelDetailUrl }}"
               class="hotel-card__img"
               style="background-image: url({{ $hotel->h_image ? asset(pare_url_file($hotel->h_image)) : asset('admin/dist/img/no-image.png') }});">

                <div class="hotel-card__img-overlay"></div>

            </a>

            <div class="hotel-card__body">
                <h3 class="hotel-card__title">
                    <a href="{{ $hotelDetailUrl }}"
                       title="{{ $hotel->h_name }}">
                        {{ the_excerpt($hotel->h_name, 70) }}
                    </a>
                </h3>

                @if($hotel->h_star_rating)
                    <div class="hotel-card__rating" aria-label="{{ $hotel->h_star_rating }} sao">
                        {{ str_repeat('★', $hotel->h_star_rating) }}
                    </div>
                @endif

                @if($hotel->h_address)
                    <div class="hotel-card__location">
                        <i class="fa fa-map-marker"></i>
                        <span>{{ the_excerpt($hotel->h_address, 65) }}</span>
                    </div>
                @endif

                @if($hotel->h_description)
                    <p class="hotel-card__desc">{!! the_excerpt(strip_tags($hotel->h_description), 90) !!}</p>
                @endif

                @if($visibleHotelTags)
                    <div class="hotel-card__verified-amenities">
                        @foreach($visibleHotelTags as $tagLabel)
                            <span><i class="fa fa-check-circle"></i>{{ $tagLabel }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="hotel-card__divider"></div>

                <div class="hotel-card__footer">
                    <a href="{{ $hotelDetailUrl }}"
                       class="hotel-card__btn">
                        Xem chi tiết <i class="fa fa-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endif
