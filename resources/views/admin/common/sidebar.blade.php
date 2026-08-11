<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.home') }}" class="brand-link">
        <img src="{!! asset('admin/dist/img/AdminLTELogo.png') !!}"
             alt="Logo"
             class="brand-image img-circle"
             style="opacity: .9; width: 34px; height: 34px; object-fit: cover;">
        <span class="brand-text ml-2">
            <span style="font-size: 15px; font-weight: 800; letter-spacing: -.3px;">Du lịch</span>
            <span style="font-size: 13px; font-weight: 400; opacity: .65;"> Admin</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- ── Tổng quan ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'truy-cap-he-thong']))
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ isset($home_active) ? $home_active : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>
                @endif

                <!-- ── Nội dung ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-danh-muc', 'danh-sach-bai-viet', 'danh-sach-dia-diem']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Nội dung
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-danh-muc']))
                <li class="nav-item">
                    <a href="{{ route('category.index') }}" class="nav-link {{ isset($category_active) ? $category_active : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Danh mục</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-bai-viet']))
                <li class="nav-item">
                    <a href="{{ route('article.index') }}" class="nav-link {{ isset($article_active) ? $article_active : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Bài viết</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-dia-diem']))
                <li class="nav-item">
                    <a href="{{ route('location.index') }}" class="nav-link {{ isset($location_active) ? $location_active : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>Địa điểm</p>
                    </a>
                </li>
                @endif

                <!-- ── Dịch vụ ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-tour', 'danh-sach-khach-san']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Dịch vụ
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-tour']))
                <li class="nav-item">
                    <a href="{{ route('tour.index') }}" class="nav-link {{ isset($tour_active) ? $tour_active : '' }}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>Quản lý Tour</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tour.guide.index') }}" class="nav-link {{ isset($tour_guide_active) ? $tour_guide_active : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Nhân sự tour</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-khach-san']))
                <li class="nav-item">
                    <a href="{{ route('hotel.index') }}" class="nav-link {{ isset($hotel_active) ? $hotel_active : '' }}">
                        <i class="nav-icon fas fa-hotel"></i>
                        <p>Khách sạn</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('car.rental.index') }}" class="nav-link {{ isset($car_rental_active) ? $car_rental_active : '' }}">
                        <i class="nav-icon fas fa-car-side"></i>
                        <p>Thuê xe</p>
                    </a>
                </li>
                @endif

                <!-- ── Giao dịch ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-dat-tour', 'quan-ly-binh-luan']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Giao dịch
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-dat-tour']))
                <li class="nav-item">
                    <a href="{{ route('book.tour.index') }}" class="nav-link {{ isset($book_tour_active) ? $book_tour_active : '' }}">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Đặt Tour</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-binh-luan']))
                <li class="nav-item">
                    <a href="{{ route('comment.index') }}" class="nav-link {{ isset($comment_active) ? $comment_active : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Bình luận</p>
                    </a>
                </li>
                @endif

                <!-- ── Hệ thống ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-vai-tro', 'danh-sach-nguoi-dung']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Hệ thống
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-vai-tro']))
                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link {{ isset($role_active) ? $role_active : '' }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>Vai trò & Quyền</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'danh-sach-nguoi-dung']))
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ isset($user_active) ? $user_active : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Người dùng</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
