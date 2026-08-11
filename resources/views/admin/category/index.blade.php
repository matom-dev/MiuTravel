@extends('admin.layouts.main')
@section('title', 'Quản lý Danh mục')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Danh mục</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Danh mục</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Danh mục</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th>Tên Danh Mục</th>
                                <th width="15%" class="text-center">Trạng Thái</th>
                                <th width="12%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$categories->isEmpty())
                                @php $i = $categories->firstItem(); @endphp
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="text-center text-muted">{{ $i }}</td>
                                        <td class="font-weight-medium">{{ $category->c_name }}</td>
                                        <td class="text-center">
                                            @if($category->c_status == 1)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Hiển thị</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-eye-slash mr-1"></i> Ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('category.update', $category->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('category.delete', $category->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có danh mục nào.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $categories->appends($query = '')->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
