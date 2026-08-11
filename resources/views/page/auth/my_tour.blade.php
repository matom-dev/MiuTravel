@extends('page.layouts.page')
@section('title', 'Tour đã đặt | Miu Travel')
@section('style')
<style>
.account-wrap {
    min-height: calc(100vh - 260px);
    background: #f7f3ef;
    padding: 56px 0 72px;
    font-family: 'Inter', sans-serif;
}
.account-layout { display: flex; gap: 28px; align-items: flex-start; }

/* Sidebar */
.user-sidebar { width: 260px; flex-shrink: 0; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07); position: sticky; top: 24px; }
.us-profile { background: linear-gradient(135deg, #f97040 0%, #f15d30 55%, #e04820 100%); padding: 28px 20px 24px; text-align: center; position: relative; overflow: hidden; }
.us-profile::before { content: ''; position: absolute; width: 180px; height: 180px; top: -70px; right: -50px; border-radius: 50%; background: rgba(255,255,255,.1); pointer-events: none; }
.us-avatar { width: 78px; height: 78px; border-radius: 50%; border: 3px solid rgba(255,255,255,.5); overflow: hidden; margin: 0 auto 12px; background: #fff; }
.us-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
.us-name { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 3px; position: relative; z-index: 1; }
.us-email { font-size: 12px; color: rgba(255,255,255,.72); position: relative; z-index: 1; word-break: break-all; }
.us-nav { padding: 10px 0; }
.us-nav-item { display: flex; align-items: center; gap: 11px; padding: 13px 20px; font-size: 14px; font-weight: 500; color: #57534e; text-decoration: none; transition: background .18s, color .18s; border-left: 3px solid transparent; }
.us-nav-item:hover { background: #fef6f0; color: #f15d30; border-left-color: #f15d30; text-decoration: none; }
.us-nav-item.active { background: #fef3ee; color: #f15d30; font-weight: 600; border-left-color: #f15d30; text-decoration: none; }
.us-nav-ic { width: 28px; height: 28px; background: #f5f0ec; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #a8a29e; flex-shrink: 0; transition: background .18s, color .18s; }
.us-nav-item:hover .us-nav-ic, .us-nav-item.active .us-nav-ic { background: rgba(241,93,48,.12); color: #f15d30; }
.us-nav-logout { border-top: 1px solid #f5f0ec; margin-top: 6px; color: #ef4444; }
.us-nav-logout:hover { background: #fff5f5; color: #ef4444; border-left-color: #ef4444; }
.us-nav-logout .us-nav-ic { background: #fee2e2; color: #ef4444; }

/* Card */
.account-card { flex: 1; background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.07); }
.ac-head { padding: 24px 36px; border-bottom: 1px solid #f5f0ec; display: flex; align-items: center; gap: 12px; }
.ac-head-ic { width: 40px; height: 40px; background: rgba(241,93,48,.1); border-radius: 11px; display: flex; align-items: center; justify-content: center; color: #f15d30; font-size: 16px; }
.ac-head h2 { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 800; color: #1a1a1a; margin: 0; }
.ac-head p { font-size: 13px; color: #a8a29e; margin: 0; }
.ac-body { padding: 28px 36px; }

/* Tour table */
.tour-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
.tour-table thead th {
    font-size: 11.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.5px; color: #a8a29e;
    padding: 0 16px 4px; border: none; background: none;
}
.tour-table tbody tr {
    background: #faf9f8;
    border-radius: 12px;
    transition: box-shadow .2s, transform .2s;
}
.tour-table tbody tr:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    transform: translateY(-1px);
}
.tour-table tbody td {
    padding: 16px;
    border: none;
    vertical-align: middle;
    font-size: 13.5px; color: #57534e;
}
.tour-table tbody td:first-child { border-radius: 12px 0 0 12px; }
.tour-table tbody td:last-child  { border-radius: 0 12px 12px 0; }

/* Tour name */
.t-name { font-size: 14px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
.t-sub  { font-size: 12.5px; color: #a8a29e; }
.t-info-row { display: flex; flex-wrap: wrap; gap: 6px 18px; }
.t-info-item { font-size: 12.5px; color: #78716c; }
.t-info-item strong { color: #44403c; font-weight: 600; }
.t-info-item--full { width: 100%; }
.guest-summary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    background: #fff7ed;
    color: #9a3412;
    font-size: 12.5px;
    font-weight: 700;
}
.guest-breakdown {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}
.guest-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid #eee4dc;
    color: #57534e;
    font-size: 11.5px;
    font-weight: 650;
}

/* Price */
.t-price { font-size: 14px; font-weight: 700; color: #f15d30; white-space: nowrap; }
.t-price small { display: block; font-size: 11.5px; color: #a8a29e; font-weight: 400; }

/* Status badges */
.t-badge {
    display: inline-flex; align-items: center;
    padding: 5px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700; white-space: nowrap;
}
.t-badge-pending  { background: #fef9c3; color: #854d0e; }
.t-badge-confirm  { background: #dcfce7; color: #166534; }
.t-badge-paid     { background: #dbeafe; color: #1d4ed8; }
.t-badge-complete { background: #e0f2fe; color: #0c4a6e; }
.t-badge-cancel   { background: #fee2e2; color: #991b1b; }
.status-note {
    color: #78716c;
    display: block;
    font-size: 11.5px;
    line-height: 1.45;
    margin-top: 7px;
    max-width: 180px;
}

/* Cancel btn */
.t-cancel-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; border-radius: 9px;
    background: #fff; border: 1.5px solid #fca5a5;
    color: #ef4444; font-size: 12.5px; font-weight: 600;
    text-decoration: none; transition: all .18s;
}
.t-cancel-btn:hover { background: #fef2f2; border-color: #f87171; color: #b91c1c; text-decoration: none; }

/* Empty state */
.empty-state {
    text-align: center; padding: 60px 20px;
    color: #c4c0bb;
}
.empty-state i { font-size: 3.5rem; margin-bottom: 16px; display: block; }
.empty-state p { font-size: 15px; margin-bottom: 20px; }
.empty-cta {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #f97040, #f15d30);
    color: #fff; border-radius: 12px; text-decoration: none;
    font-weight: 700; font-size: 14px;
    box-shadow: 0 4px 14px rgba(241,93,48,.28);
    transition: transform .2s, box-shadow .2s;
}
.empty-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(241,93,48,.36); color: #fff; text-decoration: none; }

/* Booking ID badge */
.booking-id {
    display: inline-block;
    background: rgba(241,93,48,.08);
    color: #f15d30; font-size: 11.5px; font-weight: 700;
    padding: 2px 9px; border-radius: 6px;
    letter-spacing: .5px; margin-bottom: 6px;
}

@media (max-width: 900px) {
    .account-layout { flex-direction: column; gap: 20px; }
    .user-sidebar { width: 100%; position: static; }
    .us-nav { display: flex; flex-wrap: wrap; padding: 8px; gap: 4px; }
    .us-nav-item { border-left: none; border-radius: 8px; padding: 10px 14px; flex: 1; justify-content: center; min-width: 120px; }
    .us-nav-item.active, .us-nav-item:hover { border-left-color: transparent; }
    .ac-body { padding: 16px; }
    .ac-head { padding: 20px 20px; }
    .tour-table thead { display: none; }
    .tour-table tbody tr { display: block; border-radius: 12px; margin-bottom: 12px; }
    .tour-table tbody td { display: block; border-radius: 0 !important; padding: 10px 16px; }
}
</style>
@stop

@section('content')
<section class="hero-wrap hero-wrap-2 account-page-hero" style="background: #ffffff; background-image: none;">
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start" style="min-height:200px;padding-bottom:30px;">
            <div class="col-md-9 ftco-animate pb-5 text-left">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('page.home') }}">Trang chủ <i class="fa fa-chevron-right"></i></a></span>
                    <span>Tour đã đặt <i class="fa fa-chevron-right"></i></span>
                </p>
                <h1 class="mb-0 bread">Tour đã đặt</h1>
            </div>
        </div>
    </div>
</section>

<div class="account-wrap">
    <div class="container">
        <div class="account-layout">

            @include('page.common.sideBarUser')

            <div class="account-card" style="flex:1;">

                <div class="ac-head">
                    <div class="ac-head-ic"><i class="fa fa-map-signs"></i></div>
                    <div>
                        <h2>Danh sách tour đã đặt</h2>
                        <p>Theo dõi trạng thái và thông tin các tour bạn đã booking</p>
                    </div>
                </div>

                <div class="ac-body">

                    @if (!$bookTours->isEmpty())
                        <table class="tour-table">
                            <thead>
                                <tr>
                                    <th style="width:3%;">#</th>
                                    <th style="width:30%;">Tour</th>
                                    <th>Chi tiết</th>
                                    <th style="width:15%;">Tổng tiền</th>
                                    <th style="width:14%;text-align:center;">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = $bookTours->firstItem(); @endphp
                                @foreach($bookTours as $tour)
                                    <tr>
                                        <td style="font-weight:700;color:#c4c0bb;font-size:13px;">{{ $i }}</td>
                                        <td>
                                            <div class="booking-id">#{{ $tour->id }}</div>
                                            <div class="t-name">{{ $tour->tour->t_title }}</div>
                                            <div class="t-sub">
                                                <i class="fa fa-map-marker" style="color:#f15d30;margin-right:4px;font-size:11px;"></i>
                                                {{ $tour->tour->t_starting_gate }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="t-info-row">
                                                <div class="t-info-item">
                                                    <i class="fa fa-calendar" style="color:#f15d30;margin-right:3px;"></i>
                                                    <strong>Ngày đi mong muốn:</strong> {{ $tour->b_start_date ? \Carbon\Carbon::parse($tour->b_start_date)->format('d/m/Y') : '---' }}
                                                </div>
                                                <div class="t-info-item">
                                                    <i class="fa fa-flag-checkered" style="color:#f15d30;margin-right:3px;"></i>
                                                    <strong>Ngày về dự kiến:</strong> {{ $tour->b_end_date ? \Carbon\Carbon::parse($tour->b_end_date)->format('d/m/Y') : '---' }}
                                                </div>
                                                <div class="t-info-item">
                                                    <i class="fa fa-ticket" style="color:#f15d30;margin-right:3px;"></i>
                                                    <strong>Ngày/giờ đặt:</strong> {{ $tour->created_at ? $tour->created_at->format('d/m/Y H:i') : '---' }}
                                                </div>
                                                <div class="t-info-item t-info-item--full">
                                                    <span class="guest-summary">
                                                        <i class="fa fa-users"></i>
                                                        Tổng khách: {{ $tour->total_guests }}
                                                    </span>
                                                    <div class="guest-breakdown">
                                                        <span class="guest-pill">Người lớn: {{ (int) $tour->b_number_adults }}</span>
                                                        <span class="guest-pill">Trẻ 6-12: {{ (int) $tour->b_number_children }}</span>
                                                        <span class="guest-pill">Trẻ 2-6: {{ (int) $tour->b_number_child6 }}</span>
                                                        <span class="guest-pill">Dưới 2: {{ (int) $tour->b_number_child2 }}</span>
                                                    </div>
                                                </div>
                                                @if($tour->b_note)
                                                    <div class="t-info-item t-info-item--full">
                                                        <strong>Ghi chú:</strong> {{ $tour->b_note }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="t-price">
                                                {{ number_format($tour->total_price, 0, ',', '.') }}
                                                <small>VNĐ</small>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            @php
                                                $badgeClass = match((string)$tour->b_status) {
                                                    '1' => 't-badge-pending',
                                                    '2' => 't-badge-confirm',
                                                    '3' => 't-badge-paid',
                                                    '4' => 't-badge-complete',
                                                    '5' => 't-badge-cancel',
                                                    default => 't-badge-cancel'
                                                };
                                            @endphp
                                            @if($tour->b_status != 1)
                                                <span class="t-badge {{ $badgeClass }}">
                                                    {{ $status[$tour->b_status] ?? 'Không rõ' }}
                                                </span>
                                                <small class="status-note">Lịch trình sẽ được Miu Travel xác nhận trước khi chốt booking.</small>
                                            @else
                                                <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                                                    <span class="t-badge t-badge-pending">
                                                        {{ $status[$tour->b_status] ?? 'Chờ xác nhận' }}
                                                    </span>
                                                    <small class="status-note">Lịch trình sẽ được Miu Travel xác nhận trước khi chốt booking.</small>
                                                    <form method="POST" action="{{ route('post.cancel.order.tour', $tour->id) }}" onsubmit="return confirm('Bạn có chắc muốn hủy booking đang chờ xác nhận này?')" style="margin:0;">
                                                        @csrf
                                                        <button type="submit" class="t-cancel-btn">
                                                            <i class="fa fa-times"></i> Hủy
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="text-center mt-4">
                            {{ $bookTours->links('page.pagination.default') }}
                        </div>

                    @else
                        <div class="empty-state">
                            <i class="fa fa-suitcase"></i>
                            <p>Bạn chưa đặt tour nào. Hãy khám phá những hành trình tuyệt vời!</p>
                            <a href="{{ route('tour') }}" class="empty-cta">
                                <i class="fa fa-search"></i> Khám phá tour ngay
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('script')
@stop
