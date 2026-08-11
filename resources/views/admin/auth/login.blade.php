@extends('admin.layouts.main_auth')
@section('title', 'Đăng nhập Admin | Miu Travel')
@section('content')
    <main class="admin-travel-auth" style="--admin-auth-bg: url('{{ asset('page/images/bg_5.jpg') }}');">
        <section class="admin-auth-shell">
            <div class="admin-auth-intro">
                <a class="admin-auth-brand" href="{{ route('page.home') }}">
                    <span class="admin-auth-brand__mark"><i class="fas fa-route"></i></span>
                    <span>
                        <strong>Miu Travel</strong>
                        <small>Admin Portal</small>
                    </span>
                </a>

                <div class="admin-auth-copy">
                    <span class="admin-auth-kicker">Travel Management</span>
                    <h1>Quản trị hành trình, tour và khách sạn trong một nơi.</h1>
                    <p>Theo dõi nội dung, booking và điểm đến với giao diện rõ ràng cho đội vận hành.</p>
                </div>

                <div class="admin-auth-stats">
                    <div>
                        <strong><i class="fas fa-map-marked-alt"></i></strong>
                        <span>Tour</span>
                    </div>
                    <div>
                        <strong><i class="fas fa-hotel"></i></strong>
                        <span>Khách sạn</span>
                    </div>
                    <div>
                        <strong><i class="fas fa-calendar-check"></i></strong>
                        <span>Booking</span>
                    </div>
                </div>
            </div>

            <div class="admin-auth-card">
                <div class="admin-auth-card__header">
                    <span class="admin-auth-card__icon"><i class="fas fa-user-shield"></i></span>
                    <div>
                        <h2>Đăng nhập quản trị</h2>
                        <p>Nhập tài khoản admin để tiếp tục.</p>
                    </div>
                </div>

                @if (session('danger'))
                    <div class="admin-auth-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('danger') }}</span>
                    </div>
                @endif

                <form action="{{ url('admin/login') }}" method="post" class="admin-auth-form">
                    @csrf

                    <div class="admin-auth-field">
                        <label for="admin-email">Email quản trị</label>
                        <div class="admin-auth-input @error('email') is-invalid @enderror">
                            <i class="fas fa-envelope"></i>
                            <input
                                id="admin-email"
                                name="email"
                                type="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="admin@miutravel.vn"
                                autocomplete="email"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <span class="admin-auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="admin-auth-field">
                        <label for="admin-password">Mật khẩu</label>
                        <div class="admin-auth-input @error('password') is-invalid @enderror">
                            <i class="fas fa-lock"></i>
                            <input
                                id="admin-password"
                                name="password"
                                type="password"
                                class="form-control"
                                placeholder="Nhập mật khẩu"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                class="admin-auth-password-toggle"
                                data-password-toggle
                                aria-label="Hiển thị mật khẩu"
                                aria-pressed="false"
                            >
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="admin-auth-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="admin-auth-submit">
                        <span>Đăng nhập</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <div class="admin-auth-footer">
                        <i class="fas fa-lock"></i>
                        <span>Khu vực dành riêng cho quản trị viên.</span>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleButton = document.querySelector('[data-password-toggle]');
            var passwordInput = document.getElementById('admin-password');

            if (!toggleButton || !passwordInput) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                var isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleButton.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiển thị mật khẩu');
                toggleButton.innerHTML = isHidden
                    ? '<i class="fas fa-eye-slash"></i>'
                    : '<i class="fas fa-eye"></i>';
            });
        });
    </script>
@stop
