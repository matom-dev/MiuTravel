<div class="container-fluid">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Cột trái: Thông tin vai trò -->
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin vai trò</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }} mb-4">
                            <label for="display_name" class="font-weight-bold text-muted">Tên vai trò <sup class="text-danger">(*)</sup></label>
                            <div>
                                <input type="text" maxlength="100" class="form-control px-3 py-2" id="display_name" placeholder="Nhập tên vai trò (ví dụ: Quản trị viên, Cộng tác viên...)" name="name" value="{{ old('name',isset($role) ? $role->display_name : '') }}" style="border-radius: 5px;">
                                @if($errors->has('name'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->first('description') ? 'has-error' : '' }} mb-4">
                            <label for="description" class="font-weight-bold text-muted">Mô tả chi tiết</label>
                            <div>
                                <textarea name="description" id="description" cols="20" rows="3" class="form-control p-3" placeholder="Mô tả chức năng hoặc nhiệm vụ của vai trò..." style="border-radius: 5px; resize: vertical;">{{ old('description',isset($role) ? $role->description : '') }}</textarea>
                                @if($errors->has('description'))
                                    <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card phân quyền chi tiết -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3"><i class="fas fa-shield-alt mr-1"></i> Thiết lập phân quyền</h4>
                    </div>
                    <div class="card-body">
                        <div class="permission_role">
                            @if($permissionGroups)
                                @foreach($permissionGroups as $permissionGroup)
                                    <div class="role-group-box border rounded p-3 mb-4 bg-light">
                                        <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                            <h5 class="font-weight-bold text-dark mb-0" style="font-size: 15px;"><i class="fas fa-folder-open text-muted mr-2"></i>{{$permissionGroup->name}}</h5>
                                            <div class="d-flex gap-2" style="gap: 8px;">
                                                <button type="button" class="btn btn-xs btn-success px-2 font-weight-bold" onclick="$('.{{safeTitle($permissionGroup->name)}}').prop('checked', true); return false;">
                                                    <i class="fas fa-check-double mr-1"></i> Chọn hết
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary px-2 font-weight-bold" onclick="$('.{{safeTitle($permissionGroup->name)}}').prop('checked', false); return false;">
                                                    <i class="fas fa-times mr-1"></i> Bỏ chọn
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach($permissionGroup->permissions as $permissions)
                                                <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                                                    <div class="icheck-primary d-inline">
                                                        <input type="checkbox" class="{{safeTitle($permissionGroup->name)}}"
                                                               {{ isset($listPermission) && in_array($permissions->id, $listPermission) ? 'checked' : '' }}
                                                               value="{{$permissions->id}}" name="permissions[]" id="checkbox{{ $permissions->id }}">
                                                        <label for="checkbox{{ $permissions->id }}" class="font-weight-normal text-secondary mb-0" style="cursor: pointer;">
                                                            {{$permissions->display_name}}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Thao tác xuất bản -->
            <div class="col-md-3 admin-sticky-sidebar">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-cog text-muted mr-1"></i> Hành động</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2" style="gap: 10px;">
                            <button type="submit" name="submit" class="btn btn-primary w-100 py-2 font-weight-bold rounded">
                                <i class="fas fa-save mr-1"></i> Lưu dữ liệu
                            </button>
                            <button type="reset" name="reset" value="reset" class="btn btn-outline-secondary w-100 py-2 rounded mt-2">
                                <i class="fas fa-undo mr-1"></i> Hủy thay đổi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
