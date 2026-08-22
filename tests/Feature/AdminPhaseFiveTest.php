<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\BookTour;
use App\Models\Category;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tour;
use App\Models\TourGuide;
use App\Models\TourGuideAssignment;
use App\Models\TourSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPhaseFiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_phase_five_booking_metrics(): void
    {
        $admin = $this->adminWithPermissions(['full-quyen-quan-ly']);
        $user = User::factory()->create();
        $tour = $this->createTour();
        $this->createTour([
            't_title' => 'Tour dang cho duyet',
            't_status' => Tour::STATUS_PENDING_REVIEW,
        ]);

        $this->createBooking($tour, $user, [
            'b_status' => BookTour::STATUS_PAID,
            'b_start_date' => now()->addDays(2)->format('Y-m-d') . ' 00:00:00',
        ]);
        $this->createBooking($tour, $user, [
            'b_status' => BookTour::STATUS_COMPLETED,
            'b_start_date' => now()->subDays(10)->format('Y-m-d') . ' 00:00:00',
        ]);
        $this->createBooking($tour, $user, [
            'b_status' => BookTour::STATUS_CANCELLED,
            'b_start_date' => now()->addDays(7)->format('Y-m-d') . ' 00:00:00',
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home'))
            ->assertOk()
            ->assertSee('Tỷ lệ hủy')
            ->assertSee('Sắp khởi hành')
            ->assertSee('Tour chờ duyệt')
            ->assertSee('KPI vận hành công ty du lịch')
            ->assertSee('Giá trị TB/booking')
            ->assertSee('Khách quay lại')
            ->assertSee('Chưa phân công')
            ->assertSee('Doanh thu theo tour')
            ->assertSee(route('tour.index', ['t_status' => Tour::STATUS_PENDING_REVIEW]), false)
            ->assertSee('Đã xác nhận/thanh toán trong 3 ngày tới')
            ->assertSee('Booking sắp khởi hành')
            ->assertSee('Năm nay');
    }

    public function test_revenue_month_groups_revenue_by_tour(): void
    {
        $admin = $this->adminWithPermissions(['full-quyen-quan-ly']);
        $user = User::factory()->create();
        $highRevenueTour = $this->createTour(['t_title' => 'Tour doanh thu cao']);
        $lowRevenueTour = $this->createTour(['t_title' => 'Tour doanh thu thấp']);

        $this->createBooking($highRevenueTour, $user, [
            'b_status' => BookTour::STATUS_PAID,
            'b_number_adults' => 2,
            'b_price_adults' => 1000000,
            'created_at' => now(),
        ]);
        $this->createBooking($highRevenueTour, $user, [
            'b_status' => BookTour::STATUS_COMPLETED,
            'b_number_adults' => 1,
            'b_price_adults' => 1000000,
            'created_at' => now(),
        ]);
        $this->createBooking($lowRevenueTour, $user, [
            'b_status' => BookTour::STATUS_PAID,
            'b_number_adults' => 1,
            'b_price_adults' => 300000,
            'created_at' => now(),
        ]);
        $previousMonthBooking = $this->createBooking($highRevenueTour, $user, [
            'b_status' => BookTour::STATUS_PAID,
            'b_number_adults' => 1,
            'b_price_adults' => 9000000,
        ]);
        $previousMonthBooking->forceFill([
            'created_at' => now()->subMonth(),
            'updated_at' => now()->subMonth(),
        ])->save();

        $this->actingAs($admin, 'admins')
            ->get(route('admin.revenue.month', [
                'select_month' => now()->month,
                'select_year' => now()->year,
            ]))
            ->assertOk()
            ->assertSee('Doanh thu theo tour')
            ->assertSee('Xếp hạng doanh thu theo tour')
            ->assertSee('3.300.000 ₫')
            ->assertSee('2 tour', false)
            ->assertSeeInOrder(['Tour doanh thu cao', 'Tour doanh thu thấp']);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.revenue.month', [
                'select_month' => now()->month,
                'select_year' => now()->year,
                'sort' => 'revenue_asc',
            ]))
            ->assertOk()
            ->assertSeeInOrder(['Tour doanh thu thấp', 'Tour doanh thu cao']);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home', [
                'select_month' => now()->month,
                'select_year' => now()->year,
            ]))
            ->assertOk()
            ->assertSee('3.000.000 ₫')
            ->assertDontSee('12.000.000 ₫');
    }

    public function test_dashboard_ignores_invalid_month_filter_without_validation_banner(): void
    {
        $admin = $this->adminWithPermissions(['full-quyen-quan-ly']);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home', ['select_month' => 'Tháng 8', 'select_year' => 'abc']))
            ->assertOk()
            ->assertSee('Bảng điều khiển')
            ->assertDontSee('The select month must be an integer')
            ->assertDontSee('Vui lòng kiểm tra lại thông tin');

        $this->actingAs($admin, 'admins')
            ->get(route('admin.revenue.month', ['select_month' => 'Tháng 8', 'select_year' => 'abc', 'sort' => 'bad']))
            ->assertOk()
            ->assertSee('Doanh thu theo tour')
            ->assertDontSee('The select month must be an integer')
            ->assertDontSee('Vui lòng kiểm tra lại thông tin');
    }

    public function test_tour_calendar_shows_fixed_schedules_and_requested_booking_dates(): void
    {
        $admin = $this->adminWithPermissions(['full-quyen-quan-ly']);
        $user = User::factory()->create();
        $tour = $this->createTour(['t_title' => 'Tour calendar Phong Nha']);

        TourSchedule::create([
            'ts_tour_id' => $tour->id,
            'ts_start_date' => '2026-09-10 00:00:00',
            'ts_end_date' => '2026-09-12 23:59:59',
            'ts_number_guests' => 20,
            'ts_number_registered' => 4,
            'ts_follow' => 2,
            'ts_status' => 1,
        ]);
        $this->createBooking($tour, $user, [
            'b_code' => 'MT-2026-000777',
            'b_name' => 'Khach calendar',
            'b_start_date' => '2026-09-11 00:00:00',
            'b_status' => BookTour::STATUS_PENDING,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('tour.calendar', [
                'date_from' => '2026-09-01',
                'date_to' => '2026-09-30',
            ]))
            ->assertOk()
            ->assertSee('Tour calendar Phong Nha')
            ->assertSee('Lịch tham khảo')
            ->assertDontSee('14 còn lại')
            ->assertSee('MT-2026-000777')
            ->assertSee('Ngày khách mong muốn');
    }

    public function test_admin_can_preview_tour_and_article_without_seo_score(): void
    {
        $admin = $this->adminWithPermissions(['full-quyen-quan-ly']);
        $tour = $this->createTour([
            't_title' => 'Tour preview Phong Nha day du noi dung',
            't_image' => 'tour.jpg',
            't_content' => str_repeat('Noi dung tour day du de xem truoc. ', 12),
            't_description' => str_repeat('Mo ta lich trinh tour chi tiet. ', 6),
        ]);
        $category = Category::create([
            'c_name' => 'Tin tuc',
            'c_slug' => 'tin-tuc',
            'c_status' => 1,
            'c_type' => 2,
        ]);
        $article = Article::create([
            'a_title' => 'Bai viet preview day du thong tin can thiet',
            'a_slug' => 'bai-viet-preview-day-du-thong-tin',
            'a_active' => 1,
            'a_description' => str_repeat('Mo ta bai viet du dai. ', 8),
            'a_avatar' => 'article.jpg',
            'a_content' => str_repeat('Noi dung bai viet de xem truoc co do dai tot. ', 12),
            'a_category_id' => $category->id,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('tour.index'))
            ->assertOk()
            ->assertDontSee('SEO')
            ->assertSee(route('tour.preview', $tour->id), false);

        $this->actingAs($admin, 'admins')
            ->get(route('article.index'))
            ->assertOk()
            ->assertDontSee('SEO')
            ->assertSee(route('article.preview', $article->id), false);

        $this->actingAs($admin, 'admins')
            ->get(route('tour.preview', $tour->id))
            ->assertOk()
            ->assertSee('Preview tour')
            ->assertSee('Tour preview Phong Nha')
            ->assertDontSee('SEO score');

        $this->actingAs($admin, 'admins')
            ->get(route('article.preview', $article->id))
            ->assertOk()
            ->assertSee('Preview bài viết')
            ->assertSee('Bai viet preview')
            ->assertDontSee('SEO score');
    }

    public function test_booking_role_dashboard_only_links_to_booking_work(): void
    {
        $admin = $this->adminWithPermissions(['xem-dashboard', 'xem-dat-tour', 'cap-nhat-trang-thai-dat-tour']);
        $tour = $this->createTour();
        $this->createBooking($tour, User::factory()->create());

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home'))
            ->assertOk()
            ->assertSee('Cần xử lý')
            ->assertSee(route('book.tour.index'), false)
            ->assertDontSee(route('tour.create'), false)
            ->assertDontSee(route('admin.revenue.month'), false)
            ->assertDontSee(route('article.index'), false)
            ->assertDontSee('Tour chờ duyệt');
    }

    public function test_accounting_dashboard_only_shows_financial_scope(): void
    {
        $admin = $this->adminWithPermissions([
            'xem-dashboard',
            'xem-doanh-thu',
            'xuat-bao-cao',
            'xem-dat-tour',
            'xuat-dat-tour',
        ]);
        $tour = $this->createTour();
        $this->createBooking($tour, User::factory()->create(), [
            'b_status' => BookTour::STATUS_PAID,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home'))
            ->assertOk()
            ->assertSee('Doanh thu tháng')
            ->assertSee('KPI tài chính')
            ->assertSee('Doanh thu theo tour')
            ->assertSee('/admin/revenue-month', false)
            ->assertDontSee('Cần xử lý')
            ->assertDontSee('Tổng booking')
            ->assertDontSee('Tỷ lệ hủy')
            ->assertDontSee('Sắp khởi hành')
            ->assertDontSee('Trạng thái booking')
            ->assertDontSee('Booking gần đây')
            ->assertDontSee('Booking sắp khởi hành')
            ->assertDontSee('Chưa phân công')
            ->assertDontSee('Tỷ lệ xác nhận')
            ->assertDontSee(route('book.tour.index'), false);
    }

    public function test_content_editor_dashboard_only_links_to_content_work(): void
    {
        $admin = $this->adminWithPermissions(['xem-dashboard', 'quan-ly-noi-dung']);
        Article::create([
            'a_title' => 'Noi dung noi bat',
            'a_slug' => 'noi-dung-noi-bat',
            'a_active' => 1,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('admin.home'))
            ->assertOk()
            ->assertSee('Nội dung')
            ->assertSee(route('article.index'), false)
            ->assertDontSee(route('book.tour.index'), false)
            ->assertDontSee(route('tour.create'), false)
            ->assertDontSee(route('admin.revenue.month'), false);
    }

    public function test_tour_operator_does_not_see_publish_or_delete_actions_without_permissions(): void
    {
        $admin = $this->adminWithPermissions(['quan-ly-tour']);
        $tour = $this->createTour([
            't_title' => 'Tour can dieu hanh',
            't_status' => Tour::STATUS_PENDING_REVIEW,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('tour.index'))
            ->assertOk()
            ->assertSee(route('tour.create'), false)
            ->assertSee(route('tour.update', $tour->id), false)
            ->assertSee(route('tour.preview', $tour->id), false)
            ->assertDontSee(route('tour.publish', [
                'id' => $tour->id,
                'status' => Tour::STATUS_BOOKABLE,
            ]), false)
            ->assertDontSee(route('tour.delete', $tour->id), false);
    }

    public function test_content_editor_does_not_see_delete_article_without_delete_permission(): void
    {
        $admin = $this->adminWithPermissions(['quan-ly-noi-dung']);
        $category = Category::create([
            'c_name' => 'Tin tuc',
            'c_slug' => 'tin-tuc',
            'c_status' => 1,
            'c_type' => 2,
        ]);
        $article = Article::create([
            'a_title' => 'Bai viet bien tap',
            'a_slug' => 'bai-viet-bien-tap',
            'a_active' => 1,
            'a_category_id' => $category->id,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('article.index'))
            ->assertOk()
            ->assertSee(route('article.create'), false)
            ->assertSee(route('article.update', $article->id), false)
            ->assertSee(route('article.preview', $article->id), false)
            ->assertDontSee(route('article.delete', $article->id), false);
    }

    private function adminWithPermissions(array $permissionNames): User
    {
        $admin = User::factory()->create();
        $role = Role::create([
            'name' => 'phase-five-admin-' . $admin->id,
            'display_name' => 'Phase five admin',
        ]);

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
            ], [
                'display_name' => $permissionName,
            ]);
            $role->permissionRole()->attach($permission->id);
        }

        $admin->userRole()->attach($role->id);

        return $admin;
    }

    private function createTour(array $attributes = []): Tour
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);
        $tour = Tour::create(array_merge([
            't_title' => 'Tour Phong Nha',
            't_journeys' => 'Dong Hoi - Phong Nha',
            't_schedule' => '3 ngày 2 đêm',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_move_method' => 'Ô tô',
            't_starting_gate' => 'Đồng Hới',
            't_location_id' => $location->id,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_status' => 1,
        ], $attributes));

        $guide = TourGuide::create([
            'tg_name' => 'Nguyen Huong Dan',
            'tg_role' => 'guide',
            'tg_status' => 1,
        ]);
        TourGuideAssignment::create([
            'tga_tour_id' => $tour->id,
            'tga_guide_id' => $guide->id,
            'tga_role' => 'guide',
        ]);

        return $tour;
    }

    private function createBooking(Tour $tour, User $user, array $attributes = []): BookTour
    {
        return BookTour::create(array_merge([
            'b_tour_id' => $tour->id,
            'b_user_id' => $user->id,
            'b_name' => 'Nguyen Van A',
            'b_email' => 'a@example.com',
            'b_phone' => '0900000000',
            'b_address' => 'Dong Hoi',
            'b_start_date' => now()->addDays(5)->format('Y-m-d') . ' 00:00:00',
            'b_end_date' => now()->addDays(7)->format('Y-m-d') . ' 23:59:59',
            'b_number_adults' => 1,
            'b_number_children' => 0,
            'b_number_child6' => 0,
            'b_number_child2' => 0,
            'b_price_adults' => 1000000,
            'b_price_children' => 500000,
            'b_price_child6' => 250000,
            'b_price_child2' => 125000,
            'b_status' => BookTour::STATUS_PENDING,
        ], $attributes));
    }
}
