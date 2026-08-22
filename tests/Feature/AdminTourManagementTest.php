<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\AppNotification;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class AdminTourManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tour_form_focuses_on_program_details_without_fixed_dates(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $html = View::make('admin.tour.form', [
            'locations' => collect([$location]),
            'status' => Tour::STATUS,
            'tourLeaders' => new Collection(),
            'tourGuideStaff' => new Collection(),
            'errors' => (new ViewErrorBag())->put('default', new MessageBag()),
        ])->render();

        $this->assertStringContainsString('Tên tour', $html);
        $this->assertStringContainsString('Hành trình', $html);
        $this->assertStringContainsString('Số ngày', $html);
        $this->assertStringContainsString('Số đêm', $html);
        $this->assertStringContainsString('Giá người lớn', $html);
        $this->assertStringContainsString('Giá trẻ em', $html);
        $this->assertStringContainsString('Khuyến mãi', $html);
        $this->assertStringContainsString('Nội dung tour', $html);
        $this->assertStringContainsString('Lịch trình chi tiết', $html);
        $this->assertStringContainsString('Hướng dẫn viên phụ trách', $html);
        $this->assertStringContainsString('Ảnh đại diện', $html);
        $this->assertStringContainsString('Album ảnh', $html);
        $this->assertStringContainsString('tour-form-sidebar', $html);
        $this->assertStringNotContainsString('Admin chỉ tạo thông tin tour', $html);
        $this->assertStringNotContainsString('Còn nhận đặt: khách thấy', $html);
        $this->assertStringNotContainsString('Hệ thống sẽ hiển thị thời gian tour', $html);
        $this->assertStringNotContainsString('name="t_start_date"', $html);
        $this->assertStringNotContainsString('name="t_end_date"', $html);
    }

    public function test_admin_tour_save_ignores_fixed_start_and_end_dates(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $request = Request::create('/admin/tour/create', 'POST', [
            't_title' => 'Tour admin linh hoat',
            't_location_id' => $location->id,
            't_status' => 1,
            't_price_adults' => 2000000,
            't_price_children' => 1000000,
            't_sale' => 5,
            't_move_method' => 'Ô tô du lịch',
            't_starting_gate' => 'Đồng Hới',
            't_journeys' => 'Đồng Hới - Phong Nha',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_content' => '<p>Nội dung tour</p>',
            't_description' => '<p>Lịch trình chi tiết</p>',
            't_start_date' => '2026-09-10',
            't_end_date' => '2026-09-12',
        ]);

        $tour = (new Tour())->createOrUpdate($request);

        $this->assertSame('3 ngày 2 đêm', $tour->t_schedule);
        $this->assertNull($tour->t_start_date);
        $this->assertNull($tour->t_end_date);
    }

    public function test_admin_tour_update_returns_the_updated_model(): void
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);
        $tour = Tour::create([
            't_title' => 'Tour truoc khi cap nhat',
            't_location_id' => $location->id,
            't_journeys' => 'Dong Hoi',
            't_duration_days' => 2,
            't_duration_nights' => 1,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_status' => 1,
        ]);
        $request = Request::create('/admin/tour/update/'.$tour->id, 'POST', [
            't_title' => 'Tour sau khi cap nhat',
            't_location_id' => $location->id,
            't_journeys' => 'Dong Hoi - Phong Nha',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_price_adults' => 2000000,
            't_price_children' => 1000000,
            't_status' => 1,
        ]);

        $updatedTour = (new Tour())->createOrUpdate($request, $tour->id);

        $this->assertInstanceOf(Tour::class, $updatedTour);
        $this->assertSame($tour->id, $updatedTour->id);
        $this->assertSame('Tour sau khi cap nhat', $updatedTour->t_title);
        $this->assertSame('3 ngày 2 đêm', $updatedTour->t_schedule);
    }

    public function test_tour_created_by_operator_requires_review_before_publication(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['quan-ly-tour']);
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        $this->actingAs($admin, 'admins')
            ->post(route('tour.create'), [
                't_title' => 'Tour can duyet truoc khi hien thi',
                't_location_id' => $location->id,
                't_status' => Tour::STATUS_BOOKABLE,
                't_price_adults' => 2000000,
                't_price_children' => 1000000,
                't_journeys' => 'Dong Hoi - Phong Nha',
                't_duration_days' => 3,
                't_duration_nights' => 2,
                't_content' => '<p>Noi dung tour</p>',
                't_description' => '<p>Lich trinh chi tiet</p>',
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tours', [
            't_title' => 'Tour can duyet truoc khi hien thi',
            't_status' => Tour::STATUS_PENDING_REVIEW,
        ]);
        $this->assertDatabaseHas('app_notifications', [
            'receiver_guard' => 'admins',
            'type' => 'tour_pending_review',
            'title' => 'Có tour chờ duyệt',
        ]);

        $notification = AppNotification::where('type', 'tour_pending_review')->first();
        $this->assertNotNull($notification);
        $this->assertSame(route('tour.index', ['t_status' => Tour::STATUS_PENDING_REVIEW], false), $notification->url);
        $this->assertSame('Tour can duyet truoc khi hien thi', $notification->data['title']);
    }

    public function test_authorized_admin_can_publish_pending_tour(): void
    {
        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['duyet-xuat-ban-tour']);
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);
        $tour = Tour::create([
            't_title' => 'Tour cho duyet',
            't_location_id' => $location->id,
            't_journeys' => 'Dong Hoi',
            't_duration_days' => 2,
            't_duration_nights' => 1,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_status' => Tour::STATUS_PENDING_REVIEW,
        ]);

        $this->actingAs($admin, 'admins')
            ->patch(route('tour.publish', [
                'id' => $tour->id,
                'status' => Tour::STATUS_BOOKABLE,
            ]))
            ->assertSessionHas('success');

        $this->assertSame(Tour::STATUS_BOOKABLE, (int) $tour->fresh()->t_status);
    }

    private function grantPermissions(User $user, array $permissionNames): void
    {
        $role = Role::create([
            'name' => 'tour-admin-' . $user->id,
            'display_name' => 'Tour admin',
        ]);

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::create([
                'name' => $permissionName,
                'display_name' => $permissionName,
            ]);

            $role->permissionRole()->attach($permission->id);
        }

        $user->userRole()->attach($role->id);
    }
}
