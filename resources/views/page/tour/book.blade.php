@extends('page.layouts.page')
@section('title', 'Đặt tour')
@section('style')
<style>
/* ── BOOK PAGE ───────────────────────────── */
:root {
    --primary:   #f15d30;
    --primary-dark: #d44820;
    --primary-light: #fff3ef;
    --text-dark: #1a1a2e;
    --text-muted: #6c757d;
    --border: #e8ecf0;
    --radius: 14px;
    --shadow: 0 4px 24px rgba(0,0,0,.08);
    --shadow-hover: 0 8px 40px rgba(241,93,48,.18);
}

.book-hero {
    background: #123f55 !important;
    background-image: none !important;
    position: relative;
    min-height: auto !important;
    display: flex;
    align-items: flex-start;
    padding: 34px 0 30px;
}
.book-hero .overlay {
    display: none;
}
.book-hero .hero-inner {
    position: relative; z-index: 1;
    padding: 0;
    text-align: left;
    width: min(100% - 28px, 1480px);
    max-width: 1480px;
    margin: 0 auto;
}
.book-hero .breadcrumbs { color: #fff; font-size: 18px; font-weight: 700; line-height: 1.4; margin-bottom: 12px; }
.book-hero .breadcrumbs a { color: #fff; text-decoration: none; }
.book-hero h1 { color: #fff; font-size: 2.4rem; font-weight: 900; line-height: 1.12; margin: 0 0 12px; letter-spacing: 0; }

.book-section { padding: 42px 0 64px; background: linear-gradient(180deg, #f7f9fc 0%, #eef3f8 100%); }
.book-section .container { width: min(100% - 28px, 1480px); max-width: 1480px; }
.book-grid { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(320px, .55fr); gap: 26px; align-items: start; }

.form-card { background: #fff; border-radius: 8px; box-shadow: 0 14px 34px rgba(26,34,54,.08); overflow: hidden; border: 1px solid rgba(232,236,240,.85); }
.form-card__header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); padding: 18px 24px; display: flex; align-items: center; gap: 10px; }
.form-card__header h3 { color: #fff; font-size: 1.25rem; font-weight: 800; margin: 0; }
.form-card__header .hdr-icon { color: rgba(255,255,255,.9); font-size: 18px; }
.form-card__body { padding: 24px 28px 28px; }

.book-section-label { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0; color: var(--primary); margin: 0 0 12px; padding-bottom: 8px; border-bottom: 1px solid var(--primary-light); display: flex; align-items: center; gap: 8px; }
.field-group { margin-bottom: 16px; }
.field-group label { display: block; font-size: 14.5px; font-weight: 700; color: var(--text-dark); margin-bottom: 7px; }
.field-group label sup { color: var(--primary); margin-left: 2px; }
.field-wrap { position: relative; }
.field-wrap .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #aeb7c2; font-size: 14px; pointer-events: none; transition: color .2s; }
.field-wrap input, .field-wrap select, .field-wrap textarea { width: 100%; min-height: 46px; padding: 11px 14px 11px 42px; border: 1.5px solid #dce3ea; border-radius: 8px; font-size: 15.5px; line-height: 1.35; color: var(--text-dark); background: #fbfcfe; transition: border-color .2s, box-shadow .2s, background .2s; outline: none; font-family: inherit; }
.field-wrap textarea { resize: vertical; min-height: 92px; padding-top: 12px; }
.field-wrap input:focus, .field-wrap select:focus, .field-wrap textarea:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(241,93,48,.1); }
.field-wrap:focus-within .field-icon { color: var(--primary); }
.field-error { font-size: 13px; color: #e74c3c; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
.booking-field-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 16px; row-gap: 14px; margin-bottom: 18px; }
.booking-field-grid .field-group { margin-bottom: 0; }
.field-span-2 { grid-column: 1 / -1; }

.guests-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
.counter-wrap { display: flex; align-items: center; min-height: 42px; border: 1.5px solid #dce3ea; border-radius: 8px; overflow: hidden; background: #fbfcfe; transition: border-color .2s, box-shadow .2s; }
.counter-wrap:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(241,93,48,.1); }
.counter-btn { width: 38px; height: 42px; border: none; background: none; font-size: 18px; font-weight: 800; color: var(--text-muted); cursor: pointer; transition: color .2s, background .2s; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.counter-btn:hover { color: var(--primary); background: var(--primary-light); }
.counter-input { flex: 1; border: none !important; background: transparent !important; text-align: center; font-weight: 800; font-size: 16px; outline: none; padding: 0 !important; box-shadow: none !important; -moz-appearance: textfield; min-width: 34px; }
.counter-input::-webkit-outer-spin-button, .counter-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.guest-note { font-size: 12.5px; color: var(--text-muted); margin-top: 5px; line-height: 1.35; }
.date-business-note { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-top: 10px; }
.date-business-item { display: flex; align-items: flex-start; gap: 9px; padding: 11px 12px; border: 1px solid #e8ecf0; border-radius: 8px; background: #f8fafc; color: var(--text-dark); font-size: 13px; line-height: 1.45; }
.date-business-item i { color: var(--primary); margin-top: 3px; width: 14px; flex-shrink: 0; }
.date-business-item small { display: block; color: var(--text-muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0; margin-bottom: 2px; }
.date-business-item strong { display: block; color: var(--text-dark); font-size: 13.5px; font-weight: 800; }
.date-confirm-note { display: flex; align-items: flex-start; gap: 9px; margin-top: 10px; padding: 12px 14px; border-radius: 8px; background: var(--primary-light); color: var(--text-dark); font-size: 13.5px; line-height: 1.5; }
.date-confirm-note i { color: var(--primary); margin-top: 3px; width: 14px; flex-shrink: 0; }

.btn-book { width: 100%; min-height: 52px; padding: 14px 18px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #fff; border: none; border-radius: 8px; font-size: 17px; font-weight: 800; cursor: pointer; margin-top: 6px; transition: transform .15s, box-shadow .2s; display: flex; align-items: center; justify-content: center; gap: 9px; font-family: inherit; box-shadow: 0 10px 22px rgba(241,93,48,.22); }
.btn-book:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }

.tour-info-card { background: #fff; border-radius: 8px; box-shadow: 0 14px 34px rgba(26,34,54,.08); overflow: hidden; position: sticky; top: 24px; border: 1px solid rgba(232,236,240,.85); }
.tour-info-card .tour-thumb { width: 100%; height: 180px; object-fit: cover; display: block; }
.tour-info-card__body { padding: 18px; }
.tour-badge { display: inline-block; background: var(--primary-light); color: var(--primary); font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0; padding: 4px 9px; border-radius: 8px; margin-bottom: 8px; }
.tour-info-card__body h2 { font-size: 1.12rem; font-weight: 800; color: var(--text-dark); margin: 0 0 12px; line-height: 1.35; }
.tour-meta { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.tour-meta-row { display: flex; align-items: flex-start; gap: 8px; font-size: 13.5px; color: #444; line-height: 1.45; }
.tour-meta-row .meta-icon { color: var(--primary); width: 18px; flex-shrink: 0; margin-top: 1px; }
.tour-meta-row strong { color: var(--text-dark); min-width: 82px; display: inline-block; }

.price-section-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0; color: var(--primary); margin: 16px 0 10px; padding-bottom: 7px; border-bottom: 1px solid var(--primary-light); display: flex; align-items: center; gap: 7px; }
.price-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.price-table thead th { background: #f4f6fa; padding: 9px 10px; font-weight: 700; font-size: 10px; text-transform: uppercase; letter-spacing: 0; color: var(--text-muted); text-align: left; }
.price-table tbody td { padding: 9px 10px; border-top: 1px solid var(--border); color: var(--text-dark); font-weight: 500; }
.price-table tbody td:last-child { font-weight: 700; color: var(--primary); }
.price-table tbody tr:hover td { background: #fafbfc; }

.booking-confirm-modal { position: fixed; inset: 0; z-index: 9999; display: none; align-items: center; justify-content: center; padding: 24px; }
.booking-confirm-modal.is-open { display: flex; }
.booking-confirm-backdrop { position: absolute; inset: 0; background: rgba(15,23,42,.58); }
.booking-confirm-card { position: relative; z-index: 1; width: min(100%, 760px); max-height: calc(100vh - 48px); overflow: auto; background: #fff; border-radius: 8px; box-shadow: 0 22px 70px rgba(15,23,42,.28); }
.booking-confirm-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 22px; border-bottom: 1px solid var(--border); }
.booking-confirm-head h3 { margin: 0; color: var(--text-dark); font-size: 1.25rem; font-weight: 800; }
.booking-confirm-close { width: 36px; height: 36px; border: 1px solid var(--border); border-radius: 8px; background: #fff; color: var(--text-muted); display: flex; align-items: center; justify-content: center; cursor: pointer; }
.booking-confirm-body { padding: 20px 22px 22px; }
.confirm-tour-title { margin: 0 0 14px; color: var(--primary); font-size: 1rem; font-weight: 800; }
.confirm-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px 18px; margin-bottom: 18px; }
.confirm-item { border-bottom: 1px solid #eef1f5; padding-bottom: 8px; }
.confirm-item small { display: block; color: var(--text-muted); font-size: 12px; font-weight: 700; margin-bottom: 3px; }
.confirm-item strong { color: var(--text-dark); font-size: 14.5px; font-weight: 700; overflow-wrap: anywhere; }
.confirm-note { grid-column: 1 / -1; }
.confirm-price-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 14px; }
.confirm-price-table th { background: #f4f6fa; color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0; padding: 10px; text-align: left; }
.confirm-price-table td { padding: 10px; border-top: 1px solid var(--border); color: var(--text-dark); }
.confirm-price-table td:last-child, .confirm-price-table th:last-child { text-align: right; }
.confirm-total { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 14px; padding: 14px 16px; background: var(--primary-light); border-radius: 8px; color: var(--text-dark); font-weight: 800; }
.confirm-total strong { color: var(--primary); font-size: 1.25rem; }
.confirm-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 18px; }
.confirm-btn { min-height: 44px; border-radius: 8px; border: none; padding: 11px 16px; font-weight: 800; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
.confirm-btn-secondary { background: #eef1f5; color: var(--text-dark); }
.confirm-btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #fff; }

@media (max-width: 991px) {
    .book-section { padding: 38px 0 58px; }
    .book-section .container { width: min(100% - 24px, 1320px); }
    .book-grid { grid-template-columns: 1fr; }
    .form-card__header { padding: 22px 22px; }
    .form-card__body { padding: 22px; }
    .guests-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .tour-info-card { position: static; }
    .book-hero h1 { font-size: 1.8rem; }
}

@media (max-width: 640px) {
    .booking-field-grid,
    .guests-grid { grid-template-columns: 1fr; }
    .date-business-note { grid-template-columns: 1fr; }
    .field-span-2 { grid-column: auto; }
    .booking-confirm-modal { padding: 14px; }
    .confirm-grid { grid-template-columns: 1fr; }
    .confirm-actions { flex-direction: column-reverse; }
    .confirm-btn { width: 100%; }
}
</style>
@stop
@section('seo')@stop
@section('content')

<section class="book-hero">
    <div class="overlay"></div>
    <div class="hero-inner">
        <div class="breadcrumbs">
            <a href="{{ route('page.home') }}">Trang chủ</a>
            <i class="fa fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
            <a href="{{ route('tour') }}">Tours</a>
            <i class="fa fa-chevron-right" style="font-size:10px;margin:0 6px;"></i>
            Đặt Tour
        </div>
        <h1>Đặt Tour</h1>
    </div>
</section>

@php
    $adultPrice = $tour->t_price_adults - ($tour->t_price_adults * $tour->t_sale / 100);
    $childPrice = $tour->t_price_children - ($tour->t_price_children * $tour->t_sale / 100);
    $child6Price = $childPrice * 50 / 100;
    $child2Price = $childPrice * 25 / 100;
    $durationDays = $tour->effective_duration_days;
    $durationText = $tour->duration_text;
@endphp

<section class="book-section">
    <div class="container">
        <div class="book-grid">

            <div class="form-card">
                <div class="form-card__header">
                    <i class="fa fa-edit hdr-icon"></i>
                    <h3>Thông tin đặt tour</h3>
                </div>
                <div class="form-card__body">
                    <form id="bookTourForm" action="{{ route('post.book.tour', $tour->id) }}" method="POST">
                        @csrf

                        <div class="book-section-label"><i class="fa fa-user"></i> Thông tin cá nhân</div>

                        <div class="booking-field-grid">
                            <div class="field-group">
                                <label>Họ và tên <sup>*</sup></label>
                                <div class="field-wrap">
                                    <i class="fa fa-user field-icon"></i>
                                    <input type="text" name="b_name" value="{{ old('b_name', isset($user) ? $user->name : '') }}" placeholder="Nguyễn Văn A" required>
                                </div>
                                @if ($errors->first('b_name'))<div class="field-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('b_name') }}</div>@endif
                            </div>

                            <div class="field-group">
                                <label>Email <sup>*</sup></label>
                                <div class="field-wrap">
                                    <i class="fa fa-envelope field-icon"></i>
                                    <input type="email" name="b_email" value="{{ old('b_email', isset($user) ? $user->email : '') }}" placeholder="email@example.com" required>
                                </div>
                                @if ($errors->first('b_email'))<div class="field-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('b_email') }}</div>@endif
                            </div>

                            <div class="field-group">
                                <label>Số điện thoại <sup>*</sup></label>
                                <div class="field-wrap">
                                    <i class="fa fa-phone field-icon"></i>
                                    <input type="tel" name="b_phone" value="{{ old('b_phone', isset($user) ? $user->phone : '') }}" placeholder="0xxx xxx xxx" required>
                                </div>
                                @if ($errors->first('b_phone'))<div class="field-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('b_phone') }}</div>@endif
                            </div>

                            <div class="field-group">
                                <label>Địa chỉ <sup>*</sup></label>
                                <div class="field-wrap">
                                    <i class="fa fa-map-marker field-icon"></i>
                                    <input type="text" name="b_address" value="{{ old('b_address', isset($user) ? $user->address : '') }}" placeholder="Số nhà, đường, phường, thành phố" required>
                                </div>
                                @if ($errors->first('b_address'))<div class="field-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('b_address') }}</div>@endif
                            </div>

                            <div class="field-group field-span-2">
                                <label>Ngày khởi hành mong muốn <sup>*</sup></label>
                                <div class="field-wrap">
                                    <i class="fa fa-calendar field-icon"></i>
                                    @php
                                        $oldStartDate = old('b_start_date');
                                        $startDateValue = $oldStartDate ? date('Y-m-d', strtotime($oldStartDate)) : '';
                                    @endphp
                                    <input type="date" name="b_start_date" value="{{ $startDateValue }}" min="{{ now()->addDay()->format('Y-m-d') }}" required>
                                </div>
                                <div class="date-business-note">
                                    <div class="date-business-item">
                                        <i class="fa fa-clock-o"></i>
                                        <div>
                                            <small>Thời gian tour</small>
                                            <strong>{{ $durationText }}</strong>
                                        </div>
                                    </div>
                                    <div class="date-business-item">
                                        <i class="fa fa-flag-checkered"></i>
                                        <div>
                                            <small>Ngày về dự kiến</small>
                                            <strong id="expectedEndHint">Tự tính sau khi chọn ngày đi</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="date-confirm-note">
                                    <i class="fa fa-info-circle"></i>
                                    <span>Miu Travel sẽ liên hệ xác nhận lại lịch trình trước khi chốt booking.</span>
                                </div>
                                @if ($errors->first('b_start_date'))<div class="field-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('b_start_date') }}</div>@endif
                            </div>
                        </div>

                        <div class="book-section-label" style="margin-top:18px;"><i class="fa fa-users"></i> Số lượng khách</div>

                        <div class="guests-grid">
                            <div class="field-group">
                                <label>Người lớn <sup>*</sup></label>
                                <div class="counter-wrap">
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_adults',-1)">−</button>
                                    <input type="number" name="b_number_adults" id="b_number_adults" class="counter-input" value="{{ old('b_number_adults', 1) }}" min="1" required>
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_adults',1)">+</button>
                                </div>
                                <div class="guest-note">Trên 12 tuổi</div>
                            </div>
                            <div class="field-group">
                                <label>Trẻ em <sup>*</sup></label>
                                <div class="counter-wrap">
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_children',-1)">−</button>
                                    <input type="number" name="b_number_children" id="b_number_children" class="counter-input" value="{{ old('b_number_children', 0) }}" min="0" required>
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_children',1)">+</button>
                                </div>
                                <div class="guest-note">6 – 12 tuổi</div>
                            </div>
                            <div class="field-group">
                                <label>Trẻ nhỏ <sup>*</sup></label>
                                <div class="counter-wrap">
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_child6',-1)">−</button>
                                    <input type="number" name="b_number_child6" id="b_number_child6" class="counter-input" value="{{ old('b_number_child6', 0) }}" min="0" required>
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_child6',1)">+</button>
                                </div>
                                <div class="guest-note">2 – 6 tuổi</div>
                            </div>
                            <div class="field-group">
                                <label>Sơ sinh <sup>*</sup></label>
                                <div class="counter-wrap">
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_child2',-1)">−</button>
                                    <input type="number" name="b_number_child2" id="b_number_child2" class="counter-input" value="{{ old('b_number_child2', 0) }}" min="0" required>
                                    <button type="button" class="counter-btn" onclick="changeCount('b_number_child2',1)">+</button>
                                </div>
                                <div class="guest-note">Dưới 2 tuổi</div>
                            </div>
                        </div>

                        <div class="book-section-label" style="margin-top:4px;"><i class="fa fa-comment"></i> Ghi chú</div>
                        <div class="field-group">
                            <div class="field-wrap">
                                <i class="fa fa-comment field-icon" style="top:18px;transform:none;"></i>
                                <textarea name="b_note" placeholder="Yêu cầu đặc biệt, dị ứng thực phẩm, cần hỗ trợ... Chúng tôi sẽ cố gắng đáp ứng!">{{ old('b_note') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn-book">
                            <i class="fa fa-check-square-o"></i> Kiểm tra thông tin đặt tour
                        </button>
                    </form>
                </div>
            </div>

            <div class="tour-info-card">
                <img src="{{ $tour->t_image ? asset(pare_url_file($tour->t_image)) : asset('admin/dist/img/no-image.png') }}" alt="{{ $tour->t_title }}" class="tour-thumb">
                <div class="tour-info-card__body">
                    <span class="tour-badge">{{ isset($tour->location) ? $tour->location->l_name : 'Việt Nam' }}</span>
                    <h2>{{ $tour->t_title }}</h2>
                    <div class="tour-meta">
                        <div class="tour-meta-row">
                            <i class="fa fa-road meta-icon"></i>
                            <div><strong>Hành trình:</strong> {{ $tour->t_journeys }}</div>
                        </div>
                        <div class="tour-meta-row">
                            <i class="fa fa-calendar meta-icon"></i>
                            <div><strong>Thời gian:</strong> {{ $durationText }}</div>
                        </div>
                        <div class="tour-meta-row">
                            <i class="fa fa-bus meta-icon"></i>
                            <div><strong>Vận chuyển:</strong> {{ $tour->t_move_method }}</div>
                        </div>
                    </div>

                    <div class="price-section-label"><i class="fa fa-tag"></i> Bảng giá</div>
                    <table class="price-table">
                        <thead>
                            <tr><th>Độ tuổi</th><th>Giá / người</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>👤 Người lớn (trên 12)</td>
                                <td>{{ number_format($adultPrice,0,',','.') }} ₫</td>
                            </tr>
                            <tr>
                                <td>🧒 Trẻ em (6–12)</td>
                                <td>{{ number_format($childPrice,0,',','.') }} ₫</td>
                            </tr>
                            <tr>
                                <td>👶 Trẻ nhỏ (2–6)</td>
                                <td>{{ number_format($child6Price,0,',','.') }} ₫</td>
                            </tr>
                            <tr>
                                <td>🍼 Sơ sinh (&lt;2)</td>
                                <td>{{ number_format($child2Price,0,',','.') }} ₫</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>

<div class="booking-confirm-modal" id="bookingConfirmModal" aria-hidden="true">
    <div class="booking-confirm-backdrop" data-confirm-close></div>
    <div class="booking-confirm-card" role="dialog" aria-modal="true" aria-labelledby="bookingConfirmTitle">
        <div class="booking-confirm-head">
            <h3 id="bookingConfirmTitle">Kiểm tra thông tin đặt tour</h3>
            <button type="button" class="booking-confirm-close" data-confirm-close aria-label="Đóng">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="booking-confirm-body">
            <p class="confirm-tour-title">{{ $tour->t_title }}</p>

            <div class="confirm-grid">
                <div class="confirm-item"><small>Họ và tên</small><strong id="confirmName"></strong></div>
                <div class="confirm-item"><small>Email</small><strong id="confirmEmail"></strong></div>
                <div class="confirm-item"><small>Số điện thoại</small><strong id="confirmPhone"></strong></div>
                <div class="confirm-item"><small>Địa chỉ</small><strong id="confirmAddress"></strong></div>
                <div class="confirm-item"><small>Ngày khởi hành mong muốn</small><strong id="confirmStartDate"></strong></div>
                <div class="confirm-item"><small>Ngày về dự kiến</small><strong id="confirmEndDate"></strong></div>
                <div class="confirm-item"><small>Tổng số khách</small><strong id="confirmGuestTotal"></strong></div>
                <div class="confirm-item confirm-note"><small>Ghi chú</small><strong id="confirmNote"></strong></div>
            </div>

            <div class="price-section-label"><i class="fa fa-calculator"></i> Tạm tính chi phí</div>
            <table class="confirm-price-table">
                <thead>
                    <tr>
                        <th>Loại khách</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody id="confirmPriceRows"></tbody>
            </table>
            <div class="confirm-total">
                <span>Tổng tiền</span>
                <strong id="confirmGrandTotal"></strong>
            </div>

            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-btn-secondary" data-confirm-close>
                    <i class="fa fa-pencil"></i> Sửa lại
                </button>
                <button type="button" class="confirm-btn confirm-btn-primary" id="confirmSubmitBooking">
                    <i class="fa fa-check"></i> Xác nhận đặt
                </button>
            </div>
        </div>
    </div>
</div>

@stop
@section('script')
<script>
function changeCount(fieldId, delta) {
    var input = document.getElementById(fieldId);
    var min = parseInt(input.min) || 0;
    var val = parseInt(input.value) || 0;
    input.value = Math.max(min, val + delta);
}

(function () {
    var form = document.getElementById('bookTourForm');
    var modal = document.getElementById('bookingConfirmModal');
    var confirmButton = document.getElementById('confirmSubmitBooking');
    var priceRows = document.getElementById('confirmPriceRows');
    var isConfirmed = false;

    if (!form || !modal || !confirmButton || !priceRows) {
        return;
    }

    var prices = {
        adults: Number(@json($adultPrice)),
        children: Number(@json($childPrice)),
        child6: Number(@json($child6Price)),
        child2: Number(@json($child2Price))
    };
    var durationDays = Math.max(1, Number(@json($durationDays)) || 1);
    var expectedEndHint = document.getElementById('expectedEndHint');

    function field(name) {
        return form.elements[name];
    }

    function textValue(name) {
        return field(name) ? String(field(name).value || '').trim() : '';
    }

    function numberValue(name) {
        var input = field(name);
        var min = input ? parseInt(input.min, 10) || 0 : 0;
        var value = input ? parseInt(input.value, 10) : 0;
        return Number.isFinite(value) ? Math.max(min, value) : min;
    }

    function setText(id, value) {
        var node = document.getElementById(id);
        if (node) {
            node.textContent = value || '---';
        }
    }

    function money(value) {
        return new Intl.NumberFormat('vi-VN').format(Math.round(value || 0)) + ' ₫';
    }

    function dateText(value) {
        if (!value) {
            return '---';
        }

        var parts = value.split('-');
        return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : value;
    }

    function expectedEndDate(value) {
        if (!value) {
            return '';
        }

        var date = new Date(value + 'T00:00:00');
        if (Number.isNaN(date.getTime())) {
            return '';
        }

        date.setDate(date.getDate() + durationDays - 1);
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');

        return year + '-' + month + '-' + day;
    }

    function refreshExpectedEndHint() {
        if (!expectedEndHint) {
            return;
        }

        var endDate = expectedEndDate(textValue('b_start_date'));
        expectedEndHint.textContent = endDate
            ? dateText(endDate)
            : 'Tự tính sau khi chọn ngày đi';
    }

    function openModal() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    function buildConfirmCard() {
        var guests = {
            adults: numberValue('b_number_adults'),
            children: numberValue('b_number_children'),
            child6: numberValue('b_number_child6'),
            child2: numberValue('b_number_child2')
        };
        var totalGuests = guests.adults + guests.children + guests.child6 + guests.child2;
        var lines = [
            { label: 'Người lớn (trên 12)', quantity: guests.adults, price: prices.adults },
            { label: 'Trẻ em (6-12)', quantity: guests.children, price: prices.children },
            { label: 'Trẻ nhỏ (2-6)', quantity: guests.child6, price: prices.child6 },
            { label: 'Sơ sinh (dưới 2)', quantity: guests.child2, price: prices.child2 }
        ];
        var total = 0;

        setText('confirmName', textValue('b_name'));
        setText('confirmEmail', textValue('b_email'));
        setText('confirmPhone', textValue('b_phone'));
        setText('confirmAddress', textValue('b_address'));
        setText('confirmStartDate', dateText(textValue('b_start_date')));
        setText('confirmEndDate', dateText(expectedEndDate(textValue('b_start_date'))));
        setText('confirmGuestTotal', totalGuests + ' khách');
        setText('confirmNote', textValue('b_note') || 'Không có');

        priceRows.innerHTML = lines.map(function (line) {
            var amount = line.quantity * line.price;
            total += amount;

            return '<tr>' +
                '<td>' + line.label + '</td>' +
                '<td>' + line.quantity + '</td>' +
                '<td>' + money(line.price) + '</td>' +
                '<td>' + money(amount) + '</td>' +
            '</tr>';
        }).join('');

        setText('confirmGrandTotal', money(total));
    }

    form.addEventListener('submit', function (event) {
        if (isConfirmed) {
            return;
        }

        event.preventDefault();

        if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
            return;
        }

        buildConfirmCard();
        openModal();
    });

    confirmButton.addEventListener('click', function () {
        isConfirmed = true;
        closeModal();
        form.submit();
    });

    modal.querySelectorAll('[data-confirm-close]').forEach(function (button) {
        button.addEventListener('click', closeModal);
    });

    if (field('b_start_date')) {
        field('b_start_date').addEventListener('change', refreshExpectedEndHint);
        refreshExpectedEndHint();
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) {
            closeModal();
        }
    });
})();
</script>
@stop
