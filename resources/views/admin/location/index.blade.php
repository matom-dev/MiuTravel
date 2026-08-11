@extends('admin.layouts.main')
@section('title', 'Quản lý Địa điểm')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Địa điểm</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Địa điểm</li>
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
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm Địa điểm</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-5 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Tên địa điểm</label>
                                <input type="text" name="l_name" value="{{ Request::get('l_name') }}" class="form-control" placeholder="Nhập tên địa điểm...">
                            </div>
                            <div class="col-sm-12 col-md-7 admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('location.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Địa điểm</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('location.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th>Hình Ảnh</th>
                                <th width="40%">Tên Địa Điểm</th>
                                <th class="text-center">Trạng Thái</th>
                                <th width="12%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$locations->isEmpty())
                                @php $i = $locations->firstItem(); @endphp
                                @foreach($locations as $location)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        <td class="align-middle">
                                            <div class="p-1 border rounded bg-light d-flex align-items-center justify-content-center" style="width: 150px; height: 100px; overflow: hidden;">
                                                @if(isset($location) && !empty($location->l_image))
                                                    <img src="{{ asset(pare_url_file($location->l_image)) }}" alt="{{ $location->l_name }}" class="img-fluid rounded" style="max-width: 100%; max-height: 100%; object-fit: cover; width: 100%; height: 100%;">
                                                @else
                                                    <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No image" class="img-fluid rounded opacity-50" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="font-weight-medium mb-0 text-primary">{{ $location->l_name }}</p>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($location->l_status == 1)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Nổi bật</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-eye-slash mr-1"></i> Ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('location.update', $location->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('location.delete', $location->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-map-marked-alt fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có địa điểm nào được tìm thấy.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($locations->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $locations->appends($query = '')->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
