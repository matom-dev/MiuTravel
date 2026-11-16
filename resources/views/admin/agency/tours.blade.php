@extends('admin.agency.layout')
@section('agency-heading', 'Tour & khởi hành')
@section('agency-description', 'Tạo, chỉnh sửa tour và quản lý các đợt khởi hành của đại lý.')
@section('agency-action')<a class="btn btn-light font-weight-bold" href="{{ route('agency.tour.form.create') }}"><i class="fas fa-plus mr-1"></i> Tạo tour mới</a>@endsection
@section('style-css')
@parent
<style>
.agency-tour-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:22px;align-items:start}
.agency-tour-card{border:1px solid #dfe8ed;border-radius:14px;overflow:hidden;background:#fff;box-shadow:0 4px 16px #123b5208;min-width:0}
.agency-tour-card__image{width:100%;height:190px;object-fit:cover;background:#eef4f6;display:block}
.agency-tour-card__body{padding:18px}
.agency-tour-card__heading{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:12px}
.agency-tour-card__title{font-size:19px;font-weight:700;color:#103b52;margin:0 0 10px;overflow-wrap:anywhere}
.agency-tour-card__journey{color:#617985;font-size:14px;min-height:42px;margin-bottom:14px;overflow-wrap:anywhere}
.agency-tour-card__meta{display:flex;gap:12px;flex-wrap:wrap;font-size:13px;color:#425b68;margin-bottom:14px}
.agency-tour-card__price{font-size:21px;font-weight:700;color:#127177;margin-bottom:16px}
.agency-tour-card__price small{display:block;font-size:12px;color:#617985;font-weight:400;margin-top:3px}
.agency-tour-card__edit{display:block;width:100%}
.agency-tour-card>details{margin:0;border:0;border-top:1px solid #e2e9ed;border-radius:0}
.agency-tour-card>details>summary{color:#127177;text-align:center;background:#f3faf9;font-weight:700}
.agency-tour-card:has(>details[open]){grid-column:1/-1}
.agency-tour-card:has(>details[open]) .agency-tour-card__image{height:180px}
@media(max-width:1199px){.agency-tour-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:650px){.agency-tour-grid{grid-template-columns:minmax(0,1fr)}.agency-tour-card__body{padding:15px}}
</style>
@endsection
@section('agency-body')
<section class="agency-panel" id="tours">
    <div class="agency-panel__head"><h2><i class="fas fa-map-marked-alt text-info mr-2"></i>Tour & đợt khởi hành</h2><span class="agency-muted">{{ $tours->count() }} tour của đại lý</span></div>
    <div class="agency-panel__body">
        <div class="agency-tour-grid">
        @forelse($tours as $tour)
            @php
                $openDepartureCount = $tour->schedules->filter(fn($departure) => (int)$departure->ts_status === 1
                    && \Carbon\Carbon::parse($departure->ts_start_date)->startOfDay()->gt(today())
                    && $departure->registration_deadline
                    && \Carbon\Carbon::parse($departure->registration_deadline)->endOfDay()->gte(now()))->count();
            @endphp
            <article class="agency-tour-card" aria-labelledby="agency-tour-title-{{ $tour->id }}">
                <img class="agency-tour-card__image" src="{{ $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $tour->t_title }}" loading="lazy">
                <div class="agency-tour-card__body">
                    <div class="agency-tour-card__heading"><span class="agency-muted">Tour #{{ $tour->id }}</span><span class="badge {{ $tour->status_badge_class }}">{{ $tour->status_label }}</span></div>
                    <h3 id="agency-tour-title-{{ $tour->id }}" class="agency-tour-card__title">{{ $tour->t_title }}</h3>
                    <p class="agency-tour-card__journey"><i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>{{ $tour->t_journeys ?: 'Chưa có hành trình' }}</p>
                    <div class="agency-tour-card__meta"><span><i class="far fa-clock mr-1" aria-hidden="true"></i>{{ $tour->duration_text }}</span><span><i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>{{ $openDepartureCount }} đợt đang mở</span></div>
                    <div class="agency-tour-card__price">{{ number_format($tour->t_price_adults - $tour->t_price_adults * $tour->t_sale / 100,0,',','.') }} ₫<small>Giá tham khảo / người lớn · Giá cụ thể theo đợt</small></div>
                    <a class="btn btn-outline-primary agency-tour-card__edit" href="{{ route('agency.tour.form.edit',$tour->id) }}"><i class="fas fa-edit mr-1"></i> Chỉnh sửa tour</a>
                </div>
                <details @if(request('tour_id')==$tour->id) open @endif>
                    <summary><i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i> Quản lý khởi hành <span class="small">({{ $tour->schedules->count() }} đợt)</span></summary>
                    <div class="agency-inner">
                <div class="agency-scroll"><table class="agency-table"><thead><tr><th>Ngày đi – về</th><th>Giá người lớn / trẻ em</th><th>Đã giữ / sức chứa</th><th>Lấp đầy</th><th>Trạng thái</th></tr></thead><tbody>@forelse($tour->schedules as $schedule)@php $filled=(int)($occupancy[$schedule->id]??0);$capacity=max(1,(int)$schedule->ts_number_guests); @endphp<tr><td>{{ substr($schedule->ts_start_date,0,10) }} – {{ substr($schedule->ts_end_date,0,10) }}</td><td>{{ number_format($schedule->adult_price) }} / {{ number_format($schedule->child_price) }}</td><td>{{ $filled }} / {{ $schedule->ts_number_guests }}</td><td><div class="agency-progress"><span style="width:{{ min(100,round($filled*100/$capacity)) }}%"></span></div><small>{{ round($filled*100/$capacity,1) }}%</small></td><td>{{ $schedule->ts_status?'Mở':'Đóng' }}</td></tr>@empty<tr><td colspan="5">Chưa có đợt khởi hành.</td></tr>@endforelse</tbody></table></div>
                @foreach($tour->schedules as $schedule)<details><summary>Sửa đợt {{ substr($schedule->ts_start_date,0,10) }}</summary><div class="agency-inner">@include('agency.schedule-form')</div></details>@endforeach
                <details><summary><i class="fas fa-plus mr-1"></i> Thêm đợt khởi hành</summary><div class="agency-inner">@include('agency.schedule-form',['schedule'=>new \App\Models\TourSchedule])</div></details>

                    </div>
                </details>
            </article>
        @empty
            <div><p class="text-muted">Đại lý chưa có tour nào.</p><a class="btn btn-primary" href="{{ route('agency.tour.form.create') }}">Tạo tour đầu tiên</a></div>
        @endforelse
        </div>
    </div>
</section>
@endsection
