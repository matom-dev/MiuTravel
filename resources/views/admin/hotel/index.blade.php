@extends('admin.layouts.main')
@section('title', 'Danh bạ khách sạn')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Danh bạ Khách sạn</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hotel.index') }}">Khách sạn</a></li>
                        <li class="breadcrumb-item active">Danh sách</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @php
                $adminUser = Auth::guard('admins')->user();
                $canCreateHotel = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-khach-san']);
                $canEditHotel = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-khach-san']);
                $canDeleteHotel = $adminUser && $adminUser->can(['full-quyen-quan-ly']);
            @endphp
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header border-0 bg-white pb-0">
                            <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm khách sạn</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" class="admin-search-form">
                                <div class="row align-items-end">
                                    <div class="col-sm-12 col-md-4 mb-3 mb-md-0">
                                        <label class="text-muted" style="font-size: 13px;">Tên hoặc địa chỉ</label>
                                        <input type="text" name="h_name" value="{{ Request::get('h_name') }}" class="form-control" placeholder="Nhập tên khách sạn, khu vực...">
                                    </div>
                                    <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                        <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                                        <select name="h_status" class="form-control custom-select">
                                            <option value="">Tất cả</option>
                                            @foreach($status as $key => $item)
                                                <option value="{{ $key }}" {{ (string) Request::get('h_status') === (string) $key ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-2 mb-3 mb-md-0">
                                        <label class="text-muted" style="font-size: 13px;">Sắp xếp</label>
                                        <select name="sort" class="form-control custom-select">
                                            <option value="latest" {{ Request::get('sort', 'latest') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                            <option value="oldest" {{ Request::get('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                            <option value="name_asc" {{ Request::get('sort') === 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                            <option value="name_desc" {{ Request::get('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 col-md-4 admin-search-actions">
                                        <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                        <a href="{{ route('hotel.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                            <h5 class="card-title font-weight-bold text-muted mb-0"><i class="fas fa-bed mr-1"></i> Quản lý thông tin kết nối khách sạn</h5>
                            <div class="card-tools ml-auto">
                                @if($canCreateHotel)
                                <a href="{{ route('hotel.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                                    <i class="fas fa-plus-circle mr-1"></i> Thêm mới
                                </a>
                                @endif
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">STT</th>
                                            <th width="30%">Tên khách sạn</th>
                                            <th width="15%">Hình ảnh</th>
                                            <th width="30%">Thông tin chi tiết</th>
                                            <th width="10%" class="text-center">Trạng thái</th>
                                            <th width="10%" class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$hotels->isEmpty())
                                            @php $i = $hotels->firstItem(); @endphp
                                            @foreach($hotels as $hotel)
                                                <tr>
                                                    <td class="text-center font-weight-bold text-muted">{{ $i }}</td>
                                                    <td>
                                                        <span class="font-weight-bold text-dark d-block mb-1" style="font-size: 15px;">{{ $hotel->h_name }}</span>
                                                        <span class="text-muted small"><i class="fas fa-map-marker-alt mr-1"></i>{{ $hotel->h_address }}</span>
                                                        <div class="mt-2">
                                                            <span class="badge badge-light border">{{ \App\Models\Hotel::ACCOMMODATION_TYPES[$hotel->h_accommodation_type] ?? 'Khách sạn' }}</span>
                                                            @if($hotel->h_star_rating)
                                                                <span class="small text-warning ml-1" aria-label="{{ $hotel->h_star_rating }} sao">{{ str_repeat('★', $hotel->h_star_rating) }}</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="p-1 border rounded bg-light d-flex align-items-center justify-content-center" style="width: 170px; height: 120px; overflow: hidden;">
                                                            @if(isset($hotel) && !empty($hotel->h_image))
                                                                <img src="{{ asset(pare_url_file($hotel->h_image)) }}" alt="{{ $hotel->h_name }}" class="img-fluid rounded" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            @else
                                                                <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No image" class="img-fluid rounded opacity-50" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small">
                                                            <div><strong><i class="fas fa-phone-alt text-muted mr-1"></i>Số lễ tân:</strong> <span class="text-secondary">{{ $hotel->h_phone ?: 'Chưa cập nhật' }}</span></div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-{{ $hotel->status_badge_class }} px-2 py-1">
                                                            <i class="{{ $hotel->status_icon }} mr-1"></i>{{ $hotel->status_label }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($canEditHotel || $canDeleteHotel)
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                Tác vụ
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                @if($canEditHotel)
                                                                <a class="dropdown-item text-primary" href="{{ route('hotel.update', $hotel->id) }}">
                                                                    <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                                                                </a>
                                                                @endif
                                                                @if($canEditHotel && $canDeleteHotel)
                                                                <div class="dropdown-divider"></div>
                                                                @endif
                                                                @if($canDeleteHotel)
                                                                <a class="dropdown-item text-danger btn-confirm-delete" href="{{ route('hotel.delete', $hotel->id) }}">
                                                                    <i class="fas fa-trash-alt mr-1"></i> Xóa bỏ
                                                                </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @else
                                                            <span class="text-muted">---</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $i++ @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                                    Không tìm thấy khách sạn nào
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        @if($hotels->hasPages())
                            <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                                <div class="pagination mb-0">
                                    {{ $hotels->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@stop
