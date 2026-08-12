@php
    $activeFilterCount = collect($selectedFilters)->flatten()->count();
    $resetParameters = array_filter($searchContext, function ($value) {
        return $value !== null && $value !== '';
    });
@endphp

<button type="button" class="hotel-filter-toggle" id="hotel-filter-toggle"
    aria-expanded="false" aria-controls="hotel-filter-panel">
    <span><i class="fa fa-sliders"></i> Bộ lọc</span>
    @if($activeFilterCount)
        <strong>{{ $activeFilterCount }}</strong>
    @endif
    <i class="fa fa-angle-down hotel-filter-toggle__arrow"></i>
</button>

<aside class="hotel-filter-panel" id="hotel-filter-panel">
    <form action="{{ route('hotel') }}" method="GET" id="hotel-filter-form">
        @foreach($searchContext as $contextName => $contextValue)
            @if($contextValue !== null && $contextValue !== '')
                <input type="hidden" name="{{ $contextName }}" value="{{ $contextValue }}">
            @endif
        @endforeach

        <div class="hotel-filter-panel__header">
            <div>
                <span class="hotel-filter-panel__eyebrow">Thu hẹp kết quả</span>
                <h2>Bộ lọc khách sạn</h2>
            </div>
            @if($activeFilterCount)
                <a href="{{ route('hotel', $resetParameters) }}">Xóa lọc</a>
            @endif
        </div>

        <div class="hotel-filter-group">
            <h3>Loại chỗ ở</h3>
            @foreach(\App\Models\Hotel::ACCOMMODATION_TYPES as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['types'], true);
                    $count = $filterCounts['types'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="types[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Hạng khách sạn</h3>
            @foreach(range(5, 1) as $star)
                @php
                    $checked = in_array($star, $selectedFilters['stars'], true);
                    $count = $filterCounts['stars'][$star] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="stars[]" value="{{ $star }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        <span class="hotel-filter-stars" aria-label="{{ $star }} sao">{{ str_repeat('★', $star) }}</span>
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Tiện nghi phổ biến</h3>
            @foreach(\App\Models\Hotel::AMENITIES as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['amenities'], true);
                    $count = $filterCounts['amenities'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="amenities[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Tiện nghi phòng</h3>
            @foreach(\App\Models\Hotel::ROOM_FACILITIES as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['room_facilities'], true);
                    $count = $filterCounts['room_facilities'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="room_facilities[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Chính sách lưu trú</h3>
            @foreach(\App\Models\Hotel::PROPERTY_POLICIES as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['property_policies'], true);
                    $count = $filterCounts['property_policies'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="property_policies[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Bữa ăn & dịch vụ</h3>
            @foreach(\App\Models\Hotel::MEAL_PLANS as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['meal_plans'], true);
                    $count = $filterCounts['meal_plans'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="meal_plans[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

        <div class="hotel-filter-group">
            <h3>Phù hợp với</h3>
            @foreach(\App\Models\Hotel::SUITABLE_FOR as $key => $label)
                @php
                    $checked = in_array($key, $selectedFilters['suitable_for'], true);
                    $count = $filterCounts['suitable_for'][$key] ?? 0;
                @endphp
                <label class="hotel-filter-option {{ !$count && !$checked ? 'is-disabled' : '' }}">
                    <span>
                        <input type="checkbox" name="suitable_for[]" value="{{ $key }}"
                            {{ $checked ? 'checked' : '' }} {{ !$count && !$checked ? 'disabled' : '' }}>
                        {{ $label }}
                    </span>
                    <small>{{ $count }}</small>
                </label>
            @endforeach
        </div>

    </form>
</aside>
