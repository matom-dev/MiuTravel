@extends('admin.layouts.main')
@section('title', 'Quản lý thuê xe')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-dark">Danh sách dịch vụ thuê xe</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Thuê xe</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                $adminUser = Auth::guard('admins')->user();
                $canCreateCarRental = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-thue-xe']);
                $canEditCarRental = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-thue-xe']);
                $canDeleteCarRental = $adminUser && $adminUser->can(['full-quyen-quan-ly']);
            @endphp
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h5 class="card-title font-weight-bold text-muted mb-0"><i class="fas fa-car-side mr-1"></i> Quản lý dịch vụ thuê xe</h5>
                    @if($canCreateCarRental)
                    <a href="{{ route('car.rental.create') }}" class="btn btn-primary font-weight-bold shadow-sm ml-auto">
                        <i class="fas fa-plus-circle mr-1"></i> Thêm mới
                    </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="25%">Dịch vụ</th>
                                    <th width="16%">Hình ảnh</th>
                                    <th width="34%">Thông tin xe</th>
                                    <th width="10%" class="text-center">Trạng thái</th>
                                    <th width="10%" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$carRentals->isEmpty())
                                    @php $i = $carRentals->firstItem(); @endphp
                                    @foreach($carRentals as $carRental)
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted">{{ $i }}</td>
                                            <td>
                                                <span class="font-weight-bold text-dark d-block mb-1" style="font-size:15px;">{{ $carRental->cr_name }}</span>
                                                <span class="text-muted small"><i class="fas fa-map-marker-alt mr-1"></i>{{ optional($carRental->location)->l_name ?: 'Chưa chọn địa điểm' }}</span>
                                            </td>
                                            <td>
                                                <div class="p-1 border rounded bg-light d-flex align-items-center justify-content-center" style="width:170px;height:120px;overflow:hidden;">
                                                    @if($carRental->cr_image)
                                                        <img src="{{ asset(pare_url_file($carRental->cr_image)) }}" alt="{{ $carRental->cr_name }}" class="img-fluid rounded" style="max-width:100%;max-height:100%;object-fit:contain;">
                                                    @else
                                                        <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No image" class="img-fluid rounded opacity-50" style="max-width:100%;max-height:100%;object-fit:contain;">
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="mb-1"><strong><i class="fas fa-car text-muted mr-1"></i>Loại xe:</strong> <span class="text-secondary">{{ $carRental->vehicle_type_label }}</span></div>
                                                    <div class="mb-1"><strong><i class="far fa-id-card text-muted mr-1"></i>Hình thức:</strong> <span class="text-secondary">{{ $carRental->driver_option_label }}</span></div>
                                                    <div class="mb-1"><strong><i class="fas fa-users text-muted mr-1"></i>Số chỗ:</strong> <span class="text-secondary">{{ $carRental->cr_number_seats ?: 'Đang cập nhật' }}</span></div>
                                                    <div class="mb-1"><strong><i class="fas fa-phone-alt text-muted mr-1"></i>Điện thoại:</strong> <span class="text-secondary">{{ $carRental->cr_phone ?: 'Đang cập nhật' }}</span></div>
                                                    <div><strong><i class="fas fa-map-pin text-muted mr-1"></i>Điểm nhận:</strong> <span class="text-secondary">{{ $carRental->cr_address ?: 'Đang cập nhật' }}</span></div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($carRental->cr_status == 1)
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Hiển thị</span>
                                                @else
                                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-eye-slash mr-1"></i>Ẩn</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($canEditCarRental || $canDeleteCarRental)
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        Tác vụ
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if($canEditCarRental)
                                                        <a class="dropdown-item text-primary" href="{{ route('car.rental.update', $carRental->id) }}">
                                                            <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                                                        </a>
                                                        @endif
                                                        @if($canEditCarRental && $canDeleteCarRental)
                                                        <div class="dropdown-divider"></div>
                                                        @endif
                                                        @if($canDeleteCarRental)
                                                        <a class="dropdown-item text-danger btn-confirm-delete" href="{{ route('car.rental.delete', $carRental->id) }}">
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
                                            <i class="fas fa-car-side fa-2x mb-2 d-block opacity-50"></i>
                                            Chưa có dịch vụ thuê xe nào
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($carRentals->hasPages())
                    <div class="card-footer bg-white border-top py-3 d-flex justify-content-end">
                        {{ $carRentals->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
