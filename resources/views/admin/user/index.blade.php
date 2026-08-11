@extends('admin.layouts.main')
@section('title', 'Quản lý Người Dùng')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Người Dùng</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Người dùng</li>
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
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm User</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Họ và Tên</label>
                                <input type="text" name="name" value="{{ Request::get('name') }}" class="form-control" placeholder="Nhập tên...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Email</label>
                                <input type="text" name="email" value="{{ Request::get('email') }}" class="form-control" placeholder="Nhập email...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ Request::get('phone') }}" class="form-control" placeholder="Nhập SĐT...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Vai trò (Role)</label>
                                <select class="form-control custom-select" name="role_id">
                                    <option value="">-- Tất cả vai trò --</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}" {{ Request::get('role_id') == $role->id ? 'selected' : '' }}>
                                            {{$role->display_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-right admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách -->
            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách Người Dùng</h3>
                    <div class="card-tools ml-auto">
                        <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus mr-1"></i> Thêm Mới
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped m-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">STT</th>
                                <th>Họ & Tên</th>
                                <th>Thông tin liên hệ</th>
                                <th>Vai trò (Roles)</th>
                                <th class="text-center">Trạng Thái</th>
                                <th width="12%" class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$users->isEmpty())
                                @php $i = $users->firstItem(); @endphp
                                @foreach($users as $user)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-center bg-light rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; font-weight: bold; font-size: 16px;">
                                                    {{ mb_substr($user->name, 0, 1) }}
                                                </div>
                                                <p class="font-weight-bold mb-0 text-truncate" style="max-width: 200px;" title="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="align-middle" style="font-size: 13.5px;">
                                            <div class="mb-1"><i class="fas fa-envelope text-info mr-2"></i> {{ $user->email }}</div>
                                            <div><i class="fas fa-phone-alt text-success mr-2"></i> {{ $user->phone ?: '---' }}</div>
                                        </td>
                                        <td class="align-middle">
                                            @if($user->userRole != null && count($user->userRole) > 0)
                                                @foreach($user->userRole as $role)
                                                    <span class="badge badge-primary px-2 py-1 mr-1 mb-1">{{ $role->display_name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted" style="font-size: 13px;">Chưa có quyền</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($user->status == 1)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-lock mr-1"></i> Đã khóa</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a href="{{ route('user.update', $user->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có người dùng nào được tìm thấy.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $users->appends($query = '')->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
