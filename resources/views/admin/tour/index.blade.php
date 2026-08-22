@extends('admin.layouts.main')
@section('title', 'Quản lý Tour')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Main content -->
<section class="content">
    <div class="container-fluid">
            @php
                $adminUser = Auth::guard('admins')->user();
                $canPublishTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'duyet-xuat-ban-tour']);
                $canViewTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
                $canCreateTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
                $canEditTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-tour']);
                $canDeleteTour = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'xoa-tour']);
            @endphp
            
            <!-- Form Tìm kiếm -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 bg-white pb-0">
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm Tour</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tiêu đề tour</label>
                                <input type="text" name="t_title" value="{{ Request::get('t_title') }}" class="form-control" placeholder="Nhập tên tour...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Trạng thái</label>
                                <select name="t_status" class="form-control custom-select">
                                    <option value="">Tất cả trạng thái</option>
                                    @foreach($status as $key => $item)
                                        <option value="{{ $key }}" {{ (string) Request::get('t_status') === (string) $key ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Sắp xếp</label>
                                <select name="sort" class="form-control custom-select">
                                    <option value="latest" {{ Request::get('sort', 'latest') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="oldest" {{ Request::get('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                    <option value="price_asc" {{ Request::get('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="price_desc" {{ Request::get('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-3 admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Tìm kiếm</button>
                                <a href="{{ route('tour.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Tour</h3>
                    <div class="card-tools ml-auto">
                        @if($canViewTour)
                        <a href="{{ route('tour.calendar') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-alt mr-1"></i> Calendar
                        </a>
                        @endif
                        @if($canCreateTour)
                        <a href="{{ route('tour.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm Mới
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="20%">Tiêu Đề & Hình Ảnh</th>
                                <th width="22%">Thời Gian / Giá</th>
                                <th width="24%">Vận hành / Nhân sự</th>
                                <th class="text-center">Trạng Thái</th>
                                <th width="10%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$tours->isEmpty())
                                @php $i = $tours->firstItem(); @endphp
                                @foreach($tours as $tour)
                                    @php
                                        $reservedGuests = (int) $tour->confirmed_guests_count + (int) $tour->pending_guests_count;
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        
                                        <td class="align-middle">
                                            <p class="font-weight-medium mb-2 text-truncate" style="max-width: 250px;" title="{{ $tour->t_title }}">
                                                {{ $tour->t_title }}
                                            </p>
                                            <div class="p-1 border rounded bg-light d-flex align-items-center justify-content-center" style="width: 170px; height: 110px; overflow: hidden;">
                                                @if(isset($tour) && !empty($tour->t_image))
                                                    <img src="{{ asset(pare_url_file($tour->t_image)) }}" alt="{{ $tour->t_title }}" class="img-fluid rounded" style="max-width: 100%; max-height: 100%; object-fit: cover; width: 100%; height: 100%;">
                                                @else
                                                    <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No image" class="img-fluid rounded opacity-50" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="align-middle" style="font-size: 13.5px;">
                                            <div class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-1" style="width:16px;"></i> <b>Hành trình:</b> <span class="text-muted">{{ $tour->t_journeys }}</span></div>
                                            <div class="mb-1"><i class="far fa-clock text-info mr-1" style="width:16px;"></i> <b>Thời gian:</b> <span class="text-muted">{{ $tour->duration_text }}</span></div>
                                            <div class="mb-1"><i class="fas fa-users text-primary mr-1" style="width:16px;"></i> <b>Khách đã đặt:</b> <span class="text-muted">{{ $reservedGuests }}</span></div>
                                            <div class="mb-1 text-success"><i class="fas fa-money-bill-wave mr-1" style="width:16px;"></i> <b>NL:</b> {{ number_format($tour->t_price_adults,0,',','.') }} ₫</div>
                                            <div class="text-warning"><i class="fas fa-child mr-1" style="width:16px;"></i> <b>TE:</b> {{ number_format($tour->t_price_children,0,',','.') }} ₫</div>
                                        </td>
                                        
                                        <td class="align-middle" style="font-size: 13.5px;">
                                            <div class="mb-1"><i class="fas fa-map text-primary mr-1" style="width:16px;"></i> <b>Địa điểm:</b> <span class="text-muted">{{ isset($tour->location) ? $tour->location->l_name : '---' }}</span></div>
                                            <div class="mb-1"><i class="fas fa-bus text-info mr-1" style="width:16px;"></i> <b>Di chuyển:</b> <span class="text-muted">{{ $tour->t_move_method }}</span></div>
                                            <div class="mb-1"><i class="fas fa-plane-departure text-secondary mr-1" style="width:16px;"></i> <b>Xuất phát:</b> <span class="text-muted">{{ $tour->t_starting_gate }}</span></div>
                                            <div class="mt-2">
                                                <span class="badge badge-light border mr-1"><i class="fas fa-user-clock text-warning mr-1"></i>{{ number_format($tour->pending_guests_count) }} chờ xác nhận</span>
                                                <span class="badge badge-success mr-1"><i class="fas fa-user-check mr-1"></i>{{ number_format($tour->confirmed_guests_count) }} đã xác nhận</span>
                                                <span class="badge badge-light border mr-1"><i class="fas fa-calendar-check text-info mr-1"></i>{{ number_format($tour->active_schedules_count) }} lịch chốt</span>
                                                <span class="badge badge-light border mr-1"><i class="fas fa-hiking text-primary mr-1"></i>{{ count($tour->t_activities ?: []) }} hoạt động</span>
                                                <span class="badge badge-light border"><i class="fas fa-user-tie text-success mr-1"></i>{{ number_format($tour->guide_assignments_count) }} nhân sự</span>
                                            </div>
                                            @if($tour->guideAssignments->isNotEmpty())
                                                <div class="small text-muted mt-2">
                                                    @foreach($tour->guideAssignments->take(2) as $assignment)
                                                        <div><i class="fas fa-user-tie mr-1"></i>{{ optional($assignment->guide)->tg_name ?: 'Nhân sự không tồn tại' }}</div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center align-middle">
                                            <span class="badge {{ $tour->status_badge_class }} px-2 py-1">
                                                <i class="{{ $tour->status_icon }} mr-1"></i> {{ $tour->status_label }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                {{ $tour->is_bookable ? 'Khách có thể đặt tour' : 'Không mở form đặt tour' }}
                                            </div>
                                        </td>
                                        
                                        <td class="text-center align-middle">
                                            <div class="btn-group-vertical">
                                                @if($canViewTour || $canEditTour)
                                                <a href="{{ route('tour.preview', $tour->id) }}" class="btn btn-outline-secondary btn-sm mb-1 rounded" title="Preview">
                                                    <i class="fas fa-eye"></i> Preview
                                                </a>
                                                @endif
                                                @if($canPublishTour && (int) $tour->t_status !== \App\Models\Tour::STATUS_BOOKABLE)
                                                    <form action="{{ route('tour.publish', ['id' => $tour->id, 'status' => \App\Models\Tour::STATUS_BOOKABLE]) }}" method="POST" class="mb-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm rounded w-100" title="Duyệt hiển thị">
                                                            <i class="fas fa-check-circle"></i> Duyệt
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($canPublishTour && (int) $tour->t_status === \App\Models\Tour::STATUS_BOOKABLE)
                                                    <form action="{{ route('tour.publish', ['id' => $tour->id, 'status' => \App\Models\Tour::STATUS_PAUSED]) }}" method="POST" class="mb-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-warning btn-sm rounded w-100" title="Tạm ngưng nhận đặt">
                                                            <i class="fas fa-pause-circle"></i> Tạm ngưng
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($canEditTour)
                                                    <a href="{{ route('tour.update', $tour->id) }}" class="btn btn-info btn-sm mb-1 rounded" title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </a>
                                                @endif
                                                @if($canDeleteTour)
                                                    <a href="{{ route('tour.delete', $tour->id) }}" class="btn btn-danger btn-sm btn-confirm-delete rounded" title="Xóa">
                                                        <i class="fas fa-trash-alt"></i> Xóa
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-route fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có tour nào được tìm thấy.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($tours->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $tours->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
