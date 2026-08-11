@php
    $hotelSearch = array_merge([
        'destination' => '',
        'check_in' => '',
        'check_out' => '',
        'adults' => 2,
        'children' => 0,
        'rooms' => 1,
    ], $searchContext ?? []);
@endphp

<form action="{{ route('hotel') }}" method="GET" class="hotel-connect-search" id="hotel-connect-search">
    @foreach(($selectedFilters ?? []) as $filterName => $filterValues)
        @foreach($filterValues as $filterValue)
            <input type="hidden" name="{{ $filterName }}[]" value="{{ $filterValue }}">
        @endforeach
    @endforeach

    <div class="hotel-connect-search__field hotel-connect-search__field--destination">
        <label for="hotel-destination"><i class="fa fa-map-marker"></i> Địa chỉ hoặc khách sạn</label>
        <input type="text" id="hotel-destination" name="destination"
            value="{{ old('destination', $hotelSearch['destination']) }}"
            placeholder="Ví dụ: Quảng Bình, Đồng Hới..." autocomplete="off">
    </div>

    <div class="hotel-connect-search__field">
        <label for="hotel-check-in"><i class="fa fa-calendar-check-o"></i> Nhận phòng</label>
        <input type="date" id="hotel-check-in" name="check_in"
            value="{{ old('check_in', $hotelSearch['check_in']) }}" min="{{ now()->toDateString() }}">
    </div>

    <div class="hotel-connect-search__field">
        <label for="hotel-check-out"><i class="fa fa-calendar-times-o"></i> Trả phòng</label>
        <input type="date" id="hotel-check-out" name="check_out"
            value="{{ old('check_out', $hotelSearch['check_out']) }}" min="{{ now()->addDay()->toDateString() }}">
    </div>

    <div class="hotel-connect-search__field hotel-connect-search__field--guests">
        <label><i class="fa fa-user"></i> Khách và phòng</label>
        <button type="button" class="hotel-guests-trigger" id="hotel-guests-trigger" aria-expanded="false"
            aria-controls="hotel-guests-popover">
            <span id="hotel-guests-summary">{{ $hotelSearch['adults'] }} người lớn · {{ $hotelSearch['children'] }} trẻ em · {{ $hotelSearch['rooms'] }} phòng</span>
            <i class="fa fa-angle-down" aria-hidden="true"></i>
        </button>

        <div class="hotel-guests-popover" id="hotel-guests-popover" hidden>
            @foreach([
                ['name' => 'adults', 'label' => 'Người lớn', 'min' => 1, 'max' => 30],
                ['name' => 'children', 'label' => 'Trẻ em', 'min' => 0, 'max' => 20],
                ['name' => 'rooms', 'label' => 'Phòng', 'min' => 1, 'max' => 10],
            ] as $guestField)
                <div class="hotel-guests-row">
                    <span>{{ $guestField['label'] }}</span>
                    <div class="hotel-guests-stepper">
                        <button type="button" data-step="-1" data-target="hotel-{{ $guestField['name'] }}"
                            aria-label="Giảm {{ mb_strtolower($guestField['label']) }}">
                            <i class="fa fa-minus"></i>
                        </button>
                        <input type="number" id="hotel-{{ $guestField['name'] }}" name="{{ $guestField['name'] }}"
                            value="{{ old($guestField['name'], $hotelSearch[$guestField['name']]) }}"
                            min="{{ $guestField['min'] }}" max="{{ $guestField['max'] }}">
                        <button type="button" data-step="1" data-target="hotel-{{ $guestField['name'] }}"
                            aria-label="Tăng {{ mb_strtolower($guestField['label']) }}">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="hotel-connect-search__submit">
        <i class="fa fa-search"></i>
        <span>Tìm khách sạn</span>
    </button>
</form>

@if($errors->any())
    <div class="hotel-search-errors" role="alert">
        <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
@endif

