<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        @php
            $sidebarAdmin = Auth::guard('admins')->user();
            $sidebarFullAccess = $sidebarAdmin && $sidebarAdmin->can('full-quyen-quan-ly');
            $sidebarBookingScope = $sidebarAdmin && (
                $sidebarFullAccess
                || $sidebarAdmin->can('cap-nhat-trang-thai-dat-tour')
                || $sidebarAdmin->hasRoleName(['quan-ly-van-hanh', 'nhan-vien-booking', 'cham-soc-khach-hang'])
            );
            $sidebarRevenueScope = $sidebarAdmin && $sidebarAdmin->can(['full-quyen-quan-ly', 'xem-doanh-thu']);
            $sidebarCommentScope = $sidebarAdmin && $sidebarAdmin->can(['full-quyen-quan-ly', 'quan-ly-binh-luan']);
        @endphp
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- ── Tổng quan ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'xem-dashboard']))
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ isset($home_active) ? $home_active : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Bảng điều khiển</p>
                    </a>
                </li>
                @endif

                <!-- ── Nội dung ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Nội dung
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']))
                <li class="nav-item">
                    <a href="{{ route('category.index') }}" class="nav-link {{ isset($category_active) ? $category_active : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Danh mục</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']))
                <li class="nav-item">
                    <a href="{{ route('article.index') }}" class="nav-link {{ isset($article_active) ? $article_active : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Bài viết</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-noi-dung']))
                <li class="nav-item">
                    <a href="{{ route('location.index') }}" class="nav-link {{ isset($location_active) ? $location_active : '' }}">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>Địa điểm</p>
                    </a>
                </li>
                @endif

                <!-- ── Dịch vụ ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-tour', 'quan-ly-nhan-su-tour', 'quan-ly-khach-san', 'quan-ly-thue-xe']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Dịch vụ
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-tour']))
                <li class="nav-item">
                    <a href="{{ route('tour.index') }}" class="nav-link {{ isset($tour_active) ? $tour_active : '' }}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>Quản lý Tour</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-nhan-su-tour']))
                <li class="nav-item">
                    <a href="{{ route('tour.guide.index') }}" class="nav-link {{ isset($tour_guide_active) ? $tour_guide_active : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Nhân sự tour</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-khach-san']))
                <li class="nav-item">
                    <a href="{{ route('hotel.index') }}" class="nav-link {{ isset($hotel_active) ? $hotel_active : '' }}">
                        <i class="nav-icon fas fa-hotel"></i>
                        <p>Khách sạn</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly', 'quan-ly-thue-xe']))
                <li class="nav-item">
                    <a href="{{ route('car.rental.index') }}" class="nav-link {{ isset($car_rental_active) ? $car_rental_active : '' }}">
                        <i class="nav-icon fas fa-car-side"></i>
                        <p>Thuê xe</p>
                    </a>
                </li>
                @endif

                <!-- ── Giao dịch ── -->
                @if($sidebarBookingScope || $sidebarCommentScope)
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Giao dịch
                </li>
                @endif

                @if($sidebarBookingScope)
                <li class="nav-item">
                    <a href="{{ route('book.tour.index') }}" class="nav-link {{ isset($book_tour_active) ? $book_tour_active : '' }}">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Đặt Tour</p>
                    </a>
                </li>
                @endif

                @if($sidebarCommentScope)
                <li class="nav-item">
                    <a href="{{ route('comment.index') }}" class="nav-link {{ isset($comment_active) ? $comment_active : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Bình luận</p>
                    </a>
                </li>
                @endif

                @if($sidebarRevenueScope)
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Báo cáo
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.revenue.month') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Doanh thu</p>
                    </a>
                </li>
                @endif

                <!-- ── Hệ thống ── -->
                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Hệ thống
                </li>
                @endif

                <li class="nav-header" style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.3); padding: 16px 16px 4px;">
                    Tài khoản
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.change.password') }}" class="nav-link {{ isset($admin_change_password_active) ? $admin_change_password_active : '' }}">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Đổi mật khẩu</p>
                    </a>
                </li>

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                <li class="nav-item">
                    <a href="{{ route('role.index') }}" class="nav-link {{ isset($role_active) ? $role_active : '' }}">
                        <i class="nav-icon fas fa-shield-alt"></i>
                        <p>Vai trò & Quyền</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ isset($user_staff_active) ? $user_staff_active : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Nhân sự công ty</p>
                    </a>
                </li>
                @endif

                @if(Auth::guard('admins')->user()->can(['full-quyen-quan-ly']))
                <li class="nav-item">
                    <a href="{{ route('user.customer.index') }}" class="nav-link {{ isset($user_customer_active) ? $user_customer_active : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Khách hàng</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
