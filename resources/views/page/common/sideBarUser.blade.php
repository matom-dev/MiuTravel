@php
    $sidebarUser = $user ?? auth('users')->user();
    $sidebarAvatar = $sidebarUser && !empty($sidebarUser->avatar)
        ? asset(pare_url_file($sidebarUser->avatar))
        : asset('page/images/user_default.png');
@endphp

<div class="col-lg-3">
    <div class="user-sidebar">

        {{-- Avatar & name --}}
        <div class="us-profile">
            <div class="us-avatar">
                <button type="button" class="us-avatar-view" data-avatar-viewer aria-label="Xem ảnh đại diện">
                    <img src="{{ $sidebarAvatar }}" alt="{{ $sidebarUser->name ?? 'Khách hàng' }}">
                </button>
            </div>
            <div class="us-name">{{ $sidebarUser->name ?? 'Khách hàng' }}</div>
            <div class="us-email">{{ $sidebarUser->email ?? '' }}</div>
        </div>

        {{-- Nav menu --}}
        <nav class="us-nav">
            <a href="{{ route('info.account') }}"
                class="us-nav-item {{ request()->is('thong-tin-tai-khoan.html') ? 'active' : '' }}">
                <span class="us-nav-ic"><i class="fa fa-user"></i></span>
                Thông tin tài khoản
            </a>
            <a href="{{ route('my.tour') }}"
                class="us-nav-item {{ request()->is('danh-sach-tour.html') ? 'active' : '' }}">
                <span class="us-nav-ic"><i class="fa fa-map-signs"></i></span>
                Tour đã đặt
            </a>
            <a href="{{ route('change.password') }}"
                class="us-nav-item {{ request()->is('thay-doi-mat-khau.html') ? 'active' : '' }}">
                <span class="us-nav-ic"><i class="fa fa-lock"></i></span>
                Đổi mật khẩu
            </a>
            <form method="POST" action="{{ route('page.user.logout') }}" onsubmit="return confirm('Bạn có chắc muốn đăng xuất?')" style="margin:0;">
                @csrf
                <button type="submit" class="us-nav-item us-nav-logout" style="width:100%; border:0; background:transparent; text-align:left;">
                    <span class="us-nav-ic"><i class="fa fa-sign-out"></i></span>
                    Đăng xuất
                </button>
            </form>
        </nav>

    </div>
</div>

<div class="avatar-viewer" id="avatarViewer" aria-hidden="true">
    <button type="button" class="avatar-viewer__backdrop" data-avatar-viewer-close aria-label="Đóng xem ảnh"></button>
    <div class="avatar-viewer__dialog" role="dialog" aria-modal="true" aria-label="Ảnh đại diện">
        <button type="button" class="avatar-viewer__close" data-avatar-viewer-close aria-label="Đóng">
            <i class="fa fa-times"></i>
        </button>
        <img src="" alt="Ảnh đại diện" id="avatarViewerImg">
    </div>
</div>

<style>
    .us-avatar-view {
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        border-radius: inherit;
        background: transparent;
        cursor: zoom-in;
        display: block;
    }

    .avatar-viewer {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .avatar-viewer.is-open {
        display: flex;
    }

    .avatar-viewer__backdrop {
        position: absolute;
        inset: 0;
        border: 0;
        background: rgba(8, 15, 28, .72);
        cursor: zoom-out;
    }

    .avatar-viewer__dialog {
        position: relative;
        z-index: 1;
        max-width: min(86vw, 720px);
        max-height: 86vh;
        border-radius: 18px;
        background: #fff;
        padding: 10px;
        box-shadow: 0 28px 80px rgba(0, 0, 0, .34);
    }

    .avatar-viewer__dialog img {
        display: block;
        max-width: calc(86vw - 20px);
        max-height: calc(86vh - 20px);
        border-radius: 12px;
        object-fit: contain;
    }

    .avatar-viewer__close {
        position: absolute;
        top: -14px;
        right: -14px;
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 50%;
        color: #0f172a;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .2);
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.__miuAvatarViewerReady) return;
        window.__miuAvatarViewerReady = true;

        var viewer = document.getElementById('avatarViewer');
        var viewerImg = document.getElementById('avatarViewerImg');
        if (!viewer || !viewerImg) return;

        function openViewer(src, alt) {
            if (!src) return;
            viewerImg.src = src;
            viewerImg.alt = alt || 'Ảnh đại diện';
            viewer.classList.add('is-open');
            viewer.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeViewer() {
            viewer.classList.remove('is-open');
            viewer.setAttribute('aria-hidden', 'true');
            viewerImg.src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-avatar-viewer]');
            if (!trigger) return;

            var image = trigger.matches('img') ? trigger : trigger.querySelector('img');
            openViewer(image ? image.src : trigger.getAttribute('data-avatar-viewer-src'), image ? image.alt : '');
        });

        document.addEventListener('click', function (event) {
            if (event.target.closest('[data-avatar-viewer-close]')) {
                closeViewer();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && viewer.classList.contains('is-open')) {
                closeViewer();
            }
        });
    });
</script>
