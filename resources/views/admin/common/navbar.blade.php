<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Thu/mở menu">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Center — Breadcrumb / Page info (hidden on mobile) -->
    <ul class="navbar-nav d-none d-md-flex ml-3">
        <li class="nav-item">
            <span class="nav-link text-muted" style="font-size: 13px; cursor: default;">
                <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i>
                Hệ thống đang hoạt động
            </span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">

        <li class="nav-item dropdown">
            <a class="nav-link admin-notify-toggle" data-toggle="dropdown" href="#" title="Thông báo">
                <i class="far fa-bell"></i>
                @if(($adminUnreadNotifications ?? 0) > 0)
                    <span class="badge badge-danger navbar-badge">{{ $adminUnreadNotifications > 9 ? '9+' : $adminUnreadNotifications }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right admin-notify-menu">
                <div class="dropdown-header d-flex align-items-center justify-content-between">
                    <strong>Thông báo</strong>
                    @if(($adminUnreadNotifications ?? 0) > 0)
                        <form method="POST" action="{{ route('admin.notifications.read') }}" style="margin:0;">
                            @csrf
                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                            <button type="submit" class="btn btn-link btn-sm p-0">Đã đọc tất cả</button>
                        </form>
                    @endif
                </div>
                <div class="dropdown-divider"></div>

                @forelse(($adminNotifications ?? collect()) as $notification)
                    <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" style="margin:0;">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $notification->url ?: url()->current() }}">
                        <button type="submit" class="dropdown-item admin-notify-item {{ $notification->read_at ? '' : 'is-unread' }}">
                            <span class="admin-notify-icon"><i class="fas fa-calendar-check"></i></span>
                            <span class="admin-notify-body">
                                <strong>{{ $notification->title }}</strong>
                                <small>{{ $notification->message }}</small>
                                <em>{{ $notification->created_at->diffForHumans() }}</em>
                            </span>
                        </button>
                    </form>
                    <div class="dropdown-divider m-0"></div>
                @empty
                    <div class="admin-notify-empty">
                        <i class="far fa-bell"></i>
                        <span>Chưa có thông báo mới</span>
                    </div>
                @endforelse
            </div>
        </li>

        <!-- User Menu -->
        <li class="nav-item dropdown user-menu">
            @php $adminUser = Auth::guard('admins')->user(); @endphp
            @php $adminAvatar = $adminUser && $adminUser->avatar ? asset(pare_url_file($adminUser->avatar)) : asset('/admin/dist/img/avatar5.png'); @endphp
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown"
                style="gap: 10px; padding: 0 12px !important; height: 64px;">
                <img src="{{ $adminAvatar }}" class="user-image img-circle" alt="Avatar"
                    style="width: 34px; height: 34px; object-fit: cover; border: 2px solid #e8eafe;">
                <div class="d-none d-md-block text-left" style="line-height: 1.2;">
                    <div style="font-size: 13px; font-weight: 700; color: #1a1f36;">{!! $adminUser->name !!}</div>
                    <div style="font-size: 11px; color: #6b7280;">Quản trị viên</div>
                </div>
                <i class="fas fa-chevron-down d-none d-md-block"
                    style="font-size: 10px; color: #9ca3af; margin-left: 2px;"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="margin-top: 0;">
                <!-- User image header -->
                <li class="user-header"
                    style="background: linear-gradient(135deg, #2d46c7, #4361ee); padding: 20px; height: auto;">
                    <img src="{{ $adminAvatar }}" class="img-circle" alt="Avatar"
                        style="width: 60px; height: 60px; object-fit: cover; border: 2px solid rgba(255,255,255,.5);">
                    <p style="color: #fff; margin-top: 10px; margin-bottom: 0; font-size: 15px; font-weight: 700;">
                        {!! isset($adminUser->name) ? $adminUser->name : '' !!}
                        <small
                            style="display: block; font-size: 12px; font-weight: 400; opacity: .75; margin-top: 2px;">
                            {!! isset($adminUser->email) ? $adminUser->email : '' !!}
                        </small>
                    </p>
                </li>
                <!-- Menu Footer -->
                <li class="user-footer" style="padding: 12px 16px; background: #f9fafb; border-top: 1px solid #f3f4f6;">
                    <a href="{{ route('admin.change.password') }}" class="btn btn-outline-primary btn-sm btn-block mb-2">
                        <i class="fas fa-key mr-1"></i> Đổi mật khẩu
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" onsubmit="return confirm('Bạn có chắc muốn đăng xuất?')" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm btn-block">
                            <i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
