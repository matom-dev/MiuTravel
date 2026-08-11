@extends('admin.layouts.main')
@section('title', 'Quản lý Vai trò')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Vai trò</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Vai trò</li>
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
                    <h3 class="card-title font-weight-bold">Danh sách Vai trò</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th width="20%">Tên Vai Trò</th>
                                <th width="45%">Danh Sách Quyền (Permissions)</th>
                                <th>Mô Tả</th>
                                <th width="12%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$roles->isEmpty())
                                @php $i = $roles->firstItem(); @endphp
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        <td class="align-middle">
                                            <span class="font-weight-bold text-primary">{{ $role->display_name }}</span><br>
                                            <small class="text-muted">ID: {{ $role->name }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex flex-wrap gap-1" style="gap: 5px;">
                                                @if(!empty($role->permissionRole) && count($role->permissionRole) > 0)
                                                    @foreach($role->permissionRole as $permission)
                                                        <span class="badge badge-info px-2 py-1 mb-1 font-weight-normal">{{ $permission->display_name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted font-italic">Chưa được cấp quyền</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle text-muted" style="font-size: 13.5px;">{{ $role->description ?: '---' }}</td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('role.update', $role->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('role.delete', $role->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
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
                                        <i class="fas fa-user-shield fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có vai trò nào được tạo.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($roles->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $roles->appends($query = '')->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
