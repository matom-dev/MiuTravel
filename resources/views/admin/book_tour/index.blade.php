@extends('admin.layouts.main')
@section('title', 'Quản lý Đặt Tour')
@section('content')
<style>
    .book-tour-table tbody tr.booking-row+tr.booking-row td {
        border-top: 2px solid #cfd6df;
    }

    .book-tour-table .booking-code {
        color: #111827;
        display: block;
        font-size: 16px;
        font-weight: 800;
    }

    .book-tour-table .booking-subtle {
        color: #6c757d;
        font-size: 12px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Quản lý Đặt Tour</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang
                            chủ</a></li>
                    <li class="breadcrumb-item active">Đặt tour</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Form Tìm kiếm -->
        <div class="card shadow-sm mb-4">
            <div class="card-header border-0 bg-white pb-0">
                <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm Đặt Tour
                </h3>
            </div>
            <div class="card-body">
                <form action="" method="GET" class="admin-search-form">
                    <div class="row align-items-end">
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Mã booking</label>
                            <input type="number" name="booking_id" value="{{ Request::get('booking_id') }}"
                                class="form-control" placeholder="VD: 1024">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Tên tour</label>
                            <input type="text" name="name_tour" value="{{ Request::get('name_tour') }}"
                                class="form-control" placeholder="Nhập tên tour...">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Tour</label>
                            <select class="form-control custom-select" name="b_tour_id">
                                <option value="">Tất cả tour</option>
                                @foreach($tours as $tourOption)
                                    <option value="{{$tourOption->id}}" {{ Request::get('b_tour_id') == $tourOption->id ? 'selected' : '' }}>
                                        #{{$tourOption->id}} - {{$tourOption->t_title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Tên khách</label>
                            <input type="text" name="b_name" value="{{ Request::get('b_name') }}" class="form-control"
                                placeholder="Nhập tên khách...">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Số điện thoại</label>
                            <input type="text" name="b_phone" value="{{ Request::get('b_phone') }}" class="form-control"
                                placeholder="Nhập SĐT...">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Email</label>
                            <input type="text" name="b_email" value="{{ Request::get('b_email') }}" class="form-control"
                                placeholder="Nhập email...">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Ngày khởi hành mong muốn</label>
                            <input type="date" name="b_start_date" value="{{ Request::get('b_start_date') }}"
                                class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                            <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                            <select name="b_status" class="form-control custom-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ Request::get('b_status') === 'pending' ? 'selected' : '' }}>Cần
                                    xử lý</option>
                                @foreach($status as $key => $item)
                                    <option value="{{ $key }}" {{ (string) Request::get('b_status') === (string) $key ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-right admin-search-actions">
                            <button type="submit" class="btn btn-primary admin-search-btn"><i
                                    class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                            <a href="{{ route('book.tour.index') }}" class="btn btn-secondary admin-reset-btn"><i
                                    class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách -->
        <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">Danh sách Đơn Đặt Tour</h3>
            </div>
            <div class="card-body p-0 table-responsive">
                @php $canManageBookingStatus = Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'xoa-va-cap-nhat-trang-thai']); @endphp
                <table class="table table-hover table-striped m-0 book-tour-table">
                    <thead>
                        <tr>
                            <th width="9%" class="text-center">Mã booking</th>
                            <th width="21%">Tour</th>
                            <th width="20%">Tên khách / Liên hệ</th>
                            <th width="31%">Ngày khách chọn / Chi phí</th>
                            <th class="text-center">Trạng Thái</th>
                            @if($canManageBookingStatus)
                                <th width="11%" class="text-center">Cập nhật trạng thái</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (!$bookTours->isEmpty())
                            @php $i = $bookTours->firstItem(); @endphp
                            @foreach($bookTours as $book)
                                <tr class="booking-row">
                                    <td class="text-center align-middle">
                                        <span class="booking-code">#{{ $book->id }}</span>
                                        <span class="booking-subtle">STT {{ $i }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <p class="font-weight-medium mb-1 text-primary">
                                            {{ isset($book->tour) ? $book->tour->t_title : '---' }}</p>
                                        <span class="badge badge-light border">Tour
                                            #{{ isset($book->tour) ? $book->tour->id : '---' }}</span>
                                    </td>
                                    <td class="align-middle" style="font-size: 13.5px;">
                                        <div class="mb-1"><i class="fas fa-user text-primary mr-1" style="width:16px;"></i>
                                            <b>{{ $book->b_name }}</b></div>
                                        <div class="mb-1"><i class="fas fa-envelope text-info mr-1" style="width:16px;"></i>
                                            {{ $book->b_email }}</div>
                                        <div class="mb-1"><i class="fas fa-phone-alt text-success mr-1" style="width:16px;"></i>
                                            {{ $book->b_phone }}</div>
                                        <div><i class="fas fa-map-marker-alt text-danger mr-1" style="width:16px;"></i>
                                            {{ isset($book->user) ? $book->user->address : '---' }}</div>
                                    </td>
                                    <td class="align-middle" style="font-size: 13.5px;">
                                        @php
                                            $bookingDate = $book->created_at ? $book->created_at->format('d/m/Y') : '---';
                                            $bookingTime = $book->created_at ? $book->created_at->format('H:i') : '---';
                                            $startDate = $book->b_start_date ? \Carbon\Carbon::parse($book->b_start_date)->format('d/m/Y') : '---';
                                            $endDate = $book->b_end_date ? \Carbon\Carbon::parse($book->b_end_date)->format('d/m/Y') : '---';
                                        @endphp
                                        <div class="mb-1">
                                            <i class="far fa-calendar-alt text-info mr-1" style="width:16px;"></i>
                                            Ngày đặt: {{ $bookingDate }}
                                        </div>
                                        <div class="mb-1">
                                            <i class="far fa-clock text-info mr-1" style="width:16px;"></i>
                                            Giờ đặt: {{ $bookingTime }}
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-calendar-check text-success mr-1" style="width:16px;"></i>
                                            Ngày đi mong muốn: {{ $startDate }}
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-flag-checkered text-danger mr-1" style="width:16px;"></i>
                                            Ngày về dự kiến: {{ $endDate }}
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-user-tie text-muted mr-1" style="width:16px;"></i>
                                            Người lớn: {{ $book->b_number_adults }} x
                                            {{ number_format($book->b_price_adults, 0, ',', '.') }} ₫
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-child text-muted mr-1" style="width:16px;"></i>
                                            Trẻ em: {{ $book->b_number_children }} x
                                            {{ number_format($book->b_price_children, 0, ',', '.') }} ₫
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-baby text-muted mr-1" style="width:16px;"></i>
                                            Trẻ 2-6t: {{ $book->b_number_child6 }} x
                                            {{ number_format($book->b_price_child6, 0, ',', '.') }} ₫
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-baby-carriage text-muted mr-1" style="width:16px;"></i>
                                            Dưới 2t: {{ $book->b_number_child2 }} x
                                            {{ number_format($book->b_price_child2, 0, ',', '.') }} ₫
                                        </div>
                                        <div class="mb-1 text-primary">
                                            <i class="fas fa-users mr-1" style="width:16px;"></i>
                                            Tổng khách: <b>{{ $book->total_guests }}</b>
                                        </div>
                                        <div class="mb-1 text-danger">
                                            <i class="fas fa-money-bill-wave mr-1" style="width:16px;"></i>
                                            Tổng tiền: <b>{{ number_format($book->total_price, 0, ',', '.') }} ₫</b>
                                        </div>
                                        <div class="mb-1">
                                            <i class="fas fa-map-marker-alt text-danger mr-1" style="width:16px;"></i>
                                            Điểm đón: {{ $book->b_address ?: '---' }}
                                        </div>
                                        @if($book->b_note)
                                            <div class="mt-2 text-muted font-italic">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Ghi chú: {{ $book->b_note }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span
                                            class="badge {{ str_replace('btn-', 'badge-', $classStatus[$book->b_status] ?? 'badge-secondary') }} px-2 py-1"
                                            style="font-size: 12px;">
                                            {{ $status[$book->b_status] ?? 'Không rõ' }}
                                        </span>
                                    </td>
                                    @if($canManageBookingStatus)
                                        <td class="text-center align-middle">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                                    id="dropdownMenuButton{{$book->id}}" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Thao tác
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0"
                                                    aria-labelledby="dropdownMenuButton{{$book->id}}">
                                                    <h6 class="dropdown-header">Cập nhật trạng thái</h6>
                                                    @foreach($status as $key => $item)
                                                        @php $canTransition = $book->canTransitionTo((int) $key); @endphp
                                                        <a class="dropdown-item {{ $canTransition ? 'update_book_tour' : 'disabled text-muted' }} py-2"
                                                            style="cursor: {{ $canTransition ? 'pointer' : 'not-allowed' }};"
                                                            @if($canTransition)
                                                                url="{{ route('book.tour.update.status', ['status' => $key, 'id' => $book->id]) }}"
                                                            @endif>
                                                            @if($book->b_status == $key)
                                                                <i class="fas fa-dot-circle text-primary mr-2"></i>
                                                            @else
                                                                <i class="far fa-circle text-muted mr-2"></i>
                                                            @endif
                                                            {{ $item }}
                                                        </a>
                                                    @endforeach
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger btn-confirm-delete py-2"
                                                        href="{{ route('book.tour.delete', $book->id) }}">
                                                        <i class="fas fa-trash-alt mr-2"></i> Xóa đơn đặt
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @php $i++ @endphp
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ $canManageBookingStatus ? 6 : 5 }}" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-50"></i><br>
                                    Chưa có đơn đặt tour nào.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if($bookTours->hasPages())
                <div class="card-footer bg-white border-0">
                    <div class="float-right">
                        {{ $bookTours->appends($query = '')->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@stop