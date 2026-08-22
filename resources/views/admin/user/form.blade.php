<div class="container-fluid admin-form-page">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Cột trái: Avatar -->
            <div class="col-md-3 admin-sticky-sidebar">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3">
                            <i class="fas fa-user-circle text-muted mr-1"></i> Hình đại diện
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Avatar — hiển thị tự nhiên, không bóp méo -->
                        <div class="mb-3" style="position: relative; display: inline-block;">
                            @if(isset($user) && !empty($user->avatar))
                                <img src="{{ asset(pare_url_file($user->avatar)) }}"
                                     alt="Avatar"
                                     id="image_render"
                                     class="img-circle shadow"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #e8eafe;">
                            @else
                                <img src="{{ asset('admin/dist/img/avatar5.png') }}"
                                     alt="Avatar mặc định"
                                     id="image_render"
                                     class="img-circle shadow"
                                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #e8eafe;">
                            @endif
                        </div>

                        @if(isset($user->name))
                            <h6 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h6>
                        @endif
                        @if(isset($user->email))
                            <p class="text-muted small mb-0">{{ $user->email }}</p>
                        @endif
                        @if(isset($user->userRole))
                            <span class="badge badge-primary mt-2">
                                {{ isset($user->userRole[0]) ? $user->userRole[0]->display_name : '' }}
                            </span>
                        @endif

                        <hr class="my-3">

                        <!-- Upload ảnh -->
                        <div class="form-group mb-0 text-left">
                            <label class="font-weight-bold text-muted" style="font-size: 12px;">Đổi ảnh đại diện</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="avatarFile" name="images" accept="image/*">
                                    <label class="custom-file-label" for="avatarFile">Chọn ảnh...</label>
                                </div>
                            </div>
                            @if($errors->has('images'))
                                <span class="text-danger small mt-1 d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('images') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Nút hành động -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 font-weight-bold mb-2"
                                value="{{ isset($user) ? 'update' : 'create' }}">
                            <i class="fas fa-save mr-1"></i>
                            {{ isset($user) ? 'Cập nhật người dùng' : 'Tạo người dùng mới' }}
                        </button>
                        <button type="reset" class="btn btn-outline-secondary w-100 py-2">
                            <i class="fas fa-undo mr-1"></i> Hủy thay đổi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Thông tin người dùng -->
            <div class="col-md-9">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">
                            Thông tin người dùng
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Họ và tên -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="inputName" class="font-weight-bold text-muted">
                                        Họ và tên <sup class="text-danger">(*)</sup>
                                    </label>
                                    <input type="text"
                                           class="form-control px-3 py-2"
                                           id="inputName"
                                           placeholder="Nhập họ và tên..."
                                           name="name"
                                           value="{{ old('name', isset($user->name) ? $user->name : '') }}"
                                           style="border-radius: 8px;">
                                    @if($errors->has('name'))
                                        <span class="text-danger small mt-1 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="inputEmail" class="font-weight-bold text-muted">
                                        Email <sup class="text-danger">(*)</sup>
                                    </label>
                                    <input type="email"
                                           class="form-control px-3 py-2"
                                           id="inputEmail"
                                           placeholder="Nhập địa chỉ email..."
                                           name="email"
                                           value="{{ old('email', isset($user->email) ? $user->email : '') }}"
                                           style="border-radius: 8px;">
                                    @if($errors->has('email'))
                                        <span class="text-danger small mt-1 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="inputPhone" class="font-weight-bold text-muted">
                                        Số điện thoại
                                    </label>
                                    <input type="text"
                                           class="form-control px-3 py-2"
                                           id="inputPhone"
                                           placeholder="Nhập số điện thoại..."
                                           name="phone"
                                           value="{{ old('phone', isset($user->phone) ? $user->phone : '') }}"
                                           style="border-radius: 8px;">
                                    @if($errors->has('phone'))
                                        <span class="text-danger small mt-1 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('phone') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Vai trò -->
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">
                                        Vai trò <sup class="text-danger">(*)</sup>
                                    </label>
                                    <select name="role" class="form-control custom-select px-3 py-2" style="border-radius: 8px;">
                                        <option value="">-- Chọn vai trò --</option>
                                        @if($roles)
                                            @foreach($roles as $role)
                                                <option {{ old('role', isset($listRoleUser->role_id) ? $listRoleUser->role_id : '') == $role->id ? 'selected' : '' }}
                                                        value="{{ $role->id }}">
                                                    {{ $role->display_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('role'))
                                        <span class="text-danger small mt-1 d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('role') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Mật khẩu (chỉ hiện khi tạo mới) -->
                            @if(!isset($user->password) && empty($user->password ?? ''))
                                <div class="col-md-6">
                                    <div class="form-group mb-4 {{ $errors->has('password') ? 'has-error' : '' }}">
                                        <label for="inputPassword" class="font-weight-bold text-muted">
                                            Mật khẩu <sup class="text-danger">(*)</sup>
                                        </label>
                                        <input type="password"
                                               name="password"
                                               class="form-control px-3 py-2"
                                               id="inputPassword"
                                               placeholder="Nhập mật khẩu..."
                                               style="border-radius: 8px;">
                                        @if($errors->has('password'))
                                            <span class="text-danger small mt-1 d-block">
                                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('password') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Trạng thái -->
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold text-muted d-block mb-2">Trạng thái tài khoản</label>
                                    <div class="d-flex" style="gap: 24px;">
                                        <div class="icheck-primary">
                                            <input type="radio" id="statusActive" name="status" value="1"
                                                {{ isset($user->status) && $user->status == 1 ? 'checked' : '' }}>
                                            <label for="statusActive" class="d-flex align-items-center" style="gap: 6px;">
                                                <span class="badge badge-success" style="font-size: 11px;">Hoạt động</span>
                                            </label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="radio" id="statusLocked" name="status" value="2"
                                                {{ isset($user->status) && $user->status == 2 ? 'checked' : '' }}>
                                            <label for="statusLocked" class="d-flex align-items-center" style="gap: 6px;">
                                                <span class="badge badge-danger" style="font-size: 11px;">Đã khóa</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview avatar khi chọn file
    const avatarInput = document.getElementById('avatarFile');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('image_render').src = ev.target.result;
                };
                reader.readAsDataURL(this.files[0]);
                // Cập nhật label
                const label = this.nextElementSibling;
                if (label) label.innerText = this.files[0].name;
            }
        });
    }
});
</script>
