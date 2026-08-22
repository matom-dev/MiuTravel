@extends('admin.layouts.main')
@section('title', 'Đổi mật khẩu')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="font-weight-bold">Đổi mật khẩu</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item active">Đổi mật khẩu</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6">
                <div class="card shadow-sm border-0" style="border-radius:8px;">
                    <div class="card-header bg-white border-0">
                        <h3 class="card-title font-weight-bold mb-0">
                            <i class="fas fa-key text-primary mr-1"></i> Cập nhật mật khẩu đăng nhập
                        </h3>
                    </div>
                    <form method="POST" action="{{ route('admin.update.password') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="current_password">Mật khẩu hiện tại</label>
                                <div class="input-group">
                                    <input type="password" id="current_password" name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        autocomplete="current-password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary js-toggle-password" type="button"
                                            data-target="#current_password" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('current_password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Mật khẩu mới</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary js-toggle-password" type="button"
                                            data-target="#password" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="password_confirmation">Nhập lại mật khẩu mới</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" autocomplete="new-password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary js-toggle-password" type="button"
                                            data-target="#password_confirmation" aria-label="Hiện mật khẩu" title="Hiện mật khẩu">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('script')
<script>
    $(function () {
        $('.js-toggle-password').on('click', function () {
            var $button = $(this);
            var $input = $($button.data('target'));
            var isHidden = $input.attr('type') === 'password';

            $input.attr('type', isHidden ? 'text' : 'password');
            $button.attr('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
            $button.attr('title', isHidden ? 'Ẩn mật khẩu' : 'Hiện mật khẩu');
            $button.find('i').toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@stop
