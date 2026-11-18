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
.agency-tour-card__link{display:block;color:inherit;text-decoration:none}
.agency-tour-card__link:hover{text-decoration:none;color:inherit}
.agency-tour-card__link:focus-visible{outline:3px solid #127177;outline-offset:-3px}
.agency-tour-card__title{font-size:19px;font-weight:700;color:#103b52;margin:0 0 10px;overflow-wrap:anywhere}
.agency-tour-card__excerpt{color:#617985;font-size:14px;line-height:1.5;min-height:3em;margin:0;overflow-wrap:anywhere;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
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
            <article class="agency-tour-card" aria-labelledby="agency-tour-title-{{ $tour->id }}">
                <a class="agency-tour-card__link" href="{{ route('agency.tour.form.edit', $tour->id) }}">
                    <img class="agency-tour-card__image" src="{{ $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $tour->t_title }}" loading="lazy">
                    <div class="agency-tour-card__body">
                        <h3 id="agency-tour-title-{{ $tour->id }}" class="agency-tour-card__title">{{ $tour->t_title }}</h3>
                        <p class="agency-tour-card__excerpt">{{ trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(preg_replace('/<[^>]+>/', ' ', $tour->t_content ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8'))) }}</p>
                    </div>
                </a>
            </article>
        @empty
            <div><p class="text-muted">Đại lý chưa có tour nào.</p><a class="btn btn-primary" href="{{ route('agency.tour.form.create') }}">Tạo tour đầu tiên</a></div>
        @endforelse
        </div>
    </div>
</section>
@endsection
