<div class="container-fluid admin-form-page">
    <form role="form" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h4 class="card-title font-weight-bold text-primary mb-3">Thông tin nhân sự tour</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="control-label font-weight-bold text-muted">Họ tên <sup class="text-danger">(*)</sup></label>
                            <input type="text" class="form-control px-3 py-2" name="tg_name" value="{{ old('tg_name', isset($guide) ? $guide->tg_name : '') }}" placeholder="VD: Nguyễn Minh Anh" style="border-radius: 5px;">
                            @if($errors->has('tg_name'))
                                <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_name') }}</span>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="control-label font-weight-bold text-muted">Giới tính</label>
                                    <select class="form-control custom-select px-3 py-2" name="tg_gender" style="border-radius: 5px;">
                                        <option value="">-- Chọn giới tính --</option>
                                        @foreach($genders as $key => $item)
                                            <option value="{{ $key }}" {{ old('tg_gender', isset($guide) ? $guide->tg_gender : '') == $key ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('tg_gender'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_gender') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="control-label font-weight-bold text-muted">Ngày sinh</label>
                                    <input type="date" class="form-control px-3 py-2" name="tg_birth_date" value="{{ old('tg_birth_date', isset($guide) && $guide->tg_birth_date ? $guide->tg_birth_date->format('Y-m-d') : '') }}" style="border-radius: 5px;">
                                    @if($errors->has('tg_birth_date'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_birth_date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="control-label font-weight-bold text-muted">Quê quán</label>
                                    <input type="text" class="form-control px-3 py-2" name="tg_hometown" value="{{ old('tg_hometown', isset($guide) ? $guide->tg_hometown : '') }}" placeholder="VD: Đà Nẵng" style="border-radius: 5px;">
                                    @if($errors->has('tg_hometown'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_hometown') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="control-label font-weight-bold text-muted">Số điện thoại</label>
                                    <input type="text" class="form-control px-3 py-2" name="tg_phone" value="{{ old('tg_phone', isset($guide) ? $guide->tg_phone : '') }}" placeholder="VD: 0901 234 567" style="border-radius: 5px;">
                                    @if($errors->has('tg_phone'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_phone') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="control-label font-weight-bold text-muted">Email</label>
                                    <input type="email" class="form-control px-3 py-2" name="tg_email" value="{{ old('tg_email', isset($guide) ? $guide->tg_email : '') }}" placeholder="guide@miutravel.vn" style="border-radius: 5px;">
                                    @if($errors->has('tg_email'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_email') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Kinh nghiệm</label>
                                    <input type="text" class="form-control px-3 py-2" name="tg_experience" value="{{ old('tg_experience', isset($guide) ? $guide->tg_experience : '') }}" placeholder="VD: 5 năm dẫn tour miền núi" style="border-radius: 5px;">
                                    @if($errors->has('tg_experience'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_experience') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="control-label font-weight-bold text-muted">Ngôn ngữ</label>
                                    <input type="text" class="form-control px-3 py-2" name="tg_languages" value="{{ old('tg_languages', isset($guide) ? $guide->tg_languages : '') }}" placeholder="VD: Việt, Anh" style="border-radius: 5px;">
                                    @if($errors->has('tg_languages'))
                                        <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_languages') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 admin-sticky-sidebar">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-cog text-muted mr-1"></i> Hành động</h5>
                    </div>
                    <div class="card-body">
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-2 font-weight-bold rounded">
                            <i class="fas fa-save mr-1"></i> Lưu dữ liệu
                        </button>
                        <button type="reset" name="reset" value="reset" class="btn btn-outline-secondary w-100 py-2 rounded mt-2">
                            <i class="fas fa-undo mr-1"></i> Hủy thay đổi
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-user-tag text-muted mr-1"></i> Vai trò</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-control custom-select px-3 py-2" name="tg_role" style="border-radius: 5px;">
                            @foreach($roles as $key => $item)
                                <option value="{{ $key }}" {{ old('tg_role', isset($guide) ? $guide->tg_role : 'guide') == $key ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tg_role'))
                            <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_role') }}</span>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-info-circle text-muted mr-1"></i> Trạng thái</h5>
                    </div>
                    <div class="card-body">
                        <select class="form-control custom-select px-3 py-2" name="tg_status" style="border-radius: 5px;">
                            @foreach($status as $key => $item)
                                <option value="{{ $key }}" {{ old('tg_status', isset($guide) ? $guide->tg_status : 1) == $key ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('tg_status'))
                            <span class="text-danger small mt-1 d-block"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('tg_status') }}</span>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom pb-0">
                        <h5 class="card-title font-weight-bold text-dark mb-3"><i class="fas fa-image text-muted mr-1"></i> Ảnh đại diện</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="custom-file text-left mb-3">
                            <input type="file" class="custom-file-input" id="customFile" name="images" accept="image/*">
                            <label class="custom-file-label" for="customFile">Chọn tệp...</label>
                        </div>
                        @if($errors->has('images'))
                            <span class="text-danger small mb-2 d-block text-left"><i class="fas fa-exclamation-circle mr-1"></i>{{ $errors->first('images') }}</span>
                        @endif

                        <div class="mt-3 p-2 border rounded bg-light d-flex align-items-center justify-content-center" style="min-height: 160px;">
                            @if(isset($guide) && !empty($guide->tg_photo))
                                <img src="{{ asset(pare_url_file($guide->tg_photo)) }}" alt="{{ $guide->tg_name }}" class="img-fluid rounded shadow-sm" id="image_render" style="max-height: 150px; object-fit: cover;">
                            @else
                                <img src="{{ asset('admin/dist/img/no-image.png') }}" alt="No Image" class="img-fluid rounded opacity-50" id="image_render" style="max-height: 150px; object-fit: cover;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('customFile');
        if (!fileInput) {
            return;
        }

        fileInput.addEventListener('change', function(e) {
            if (!e.target.files.length) {
                return;
            }

            e.target.nextElementSibling.innerText = e.target.files[0].name;
        });
    });
</script>
