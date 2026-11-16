<div class="small mt-2 mb-2">
    <i class="fas fa-building mr-1" aria-hidden="true"></i>
    <strong>Đơn vị sở hữu:</strong>
    @if($tour->agency_id)
        @if($tour->agency)
            <a href="{{ route('agency.public', $tour->agency->id) }}">{{ $tour->agency->name }}</a>
            <span class="text-muted">(Đại lý #{{ $tour->agency_id }})</span>
        @else
            <span class="text-muted">Đại lý #{{ $tour->agency_id }} · Không còn thông tin hồ sơ</span>
        @endif
    @else
        <span>Nền tảng Miu Travel</span>
    @endif
</div>
