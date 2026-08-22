@extends('admin.layouts.main')
@section('title', 'Quản lý Nhân sự tour')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Quản lý Nhân sự tour</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                        <li class="breadcrumb-item active">Nhân sự tour</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @php
                $adminUser = Auth::guard('admins')->user();
                $canCreateTourGuide = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-nhan-su-tour']);
                $canEditTourGuide = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-nhan-su-tour']);
                $canDeleteTourGuide = $adminUser && $adminUser->can(['full-quyen-quan-ly', 'quan-ly-nhan-su-tour', 'xoa-tour']);
            @endphp
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 bg-white pb-0">
                    <h3 class="card-title font-weight-bold text-muted"><i class="fas fa-search mr-1"></i> Tìm kiếm nhân sự</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="admin-search-form">
                        <div class="row align-items-end">
                            <div class="col-sm-12 col-md-4 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Họ tên</label>
                                <input type="text" name="tg_name" value="{{ Request::get('tg_name') }}" class="form-control" placeholder="Nhập tên nhân sự...">
                            </div>
                            <div class="col-sm-12 col-md-3 mb-3 mb-md-0">
                                <label class="text-muted" style="font-size: 13px;">Vai trò</label>
                                <select name="tg_role" class="form-control custom-select">
                                    <option value="">-- Tất cả --</option>
                                    @foreach($roles as $key => $item)
                                        <option value="{{ $key }}" {{ Request::get('tg_role') == $key ? 'selected' : '' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-5 admin-search-actions">
                                <button type="submit" class="btn btn-primary admin-search-btn"><i class="fas fa-filter mr-1"></i> Lọc dữ liệu</button>
                                <a href="{{ route('tour.guide.index') }}" class="btn btn-secondary admin-reset-btn"><i class="fas fa-sync-alt mr-1"></i> Xóa lọc</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Danh sách nhân sự tour</h3>
                    <div class="card-tools ml-auto">
                        @if($canCreateTourGuide)
                        <a href="{{ route('tour.guide.create') }}" class="btn btn-primary btn-sm">
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
                                <th width="12%">Ảnh</th>
                                <th width="24%">Nhân sự</th>
                                <th width="20%">Liên hệ</th>
                                <th>Thông tin</th>
                                <th class="text-center">Trạng thái</th>
                                <th width="10%" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$guides->isEmpty())
                                @php $i = $guides->firstItem(); @endphp
                                @foreach($guides as $guide)
                                    <tr>
                                        <td class="text-center text-muted align-middle">{{ $i }}</td>
                                        <td class="align-middle">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center overflow-hidden" style="width:64px;height:64px;">
                                                @if($guide->tg_photo)
                                                    <img src="{{ asset(pare_url_file($guide->tg_photo)) }}" alt="{{ $guide->tg_name }}" style="width:100%;height:100%;object-fit:cover;">
                                                @else
                                                    <i class="fas fa-user-tie text-muted"></i>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="font-weight-bold text-primary mb-1">{{ $guide->tg_name }}</p>
                                            <span class="badge badge-light border">{{ $roles[$guide->tg_role] ?? '---' }}</span>
                                            @if($guide->tg_gender)
                                                <span class="badge badge-light border ml-1">{{ $genders[$guide->tg_gender] ?? '---' }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle" style="font-size:13.5px;">
                                            <div class="mb-1"><i class="fas fa-phone-alt text-success mr-1" style="width:16px;"></i>{{ $guide->tg_phone ?: '---' }}</div>
                                            <div><i class="fas fa-envelope text-info mr-1" style="width:16px;"></i>{{ $guide->tg_email ?: '---' }}</div>
                                        </td>
                                        <td class="align-middle" style="font-size:13.5px;">
                                            <div class="mb-1"><b>Kinh nghiệm:</b> <span class="text-muted">{{ $guide->tg_experience ?: '---' }}</span></div>
                                            <div><b>Ngôn ngữ:</b> <span class="text-muted">{{ $guide->tg_languages ?: '---' }}</span></div>
                                            <div class="mt-1"><b>Ngày sinh:</b> <span class="text-muted">{{ $guide->tg_birth_date ? $guide->tg_birth_date->format('d/m/Y') : '---' }}</span></div>
                                            <div class="mt-1"><b>Quê quán:</b> <span class="text-muted">{{ $guide->tg_hometown ?: '---' }}</span></div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($guide->tg_status == 1)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Hoạt động</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-eye-slash mr-1"></i> Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                @if($canEditTourGuide)
                                                <a href="{{ route('tour.guide.update', $guide->id) }}" class="btn btn-info btn-sm" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endif
                                                @if($canDeleteTourGuide)
                                                <a href="{{ route('tour.guide.delete', $guide->id) }}" class="btn btn-danger btn-sm btn-confirm-delete" title="Xóa">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++ @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-user-tie fa-3x mb-3 opacity-50"></i><br>
                                        Chưa có nhân sự tour nào.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($guides->hasPages())
                    <div class="card-footer bg-white border-0">
                        <div class="float-right">
                            {{ $guides->appends($query = '')->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
