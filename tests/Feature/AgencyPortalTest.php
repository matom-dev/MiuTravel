<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\AgencyTransaction;
use App\Models\BookTour;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AgencyPortalTest extends TestCase
{
    use RefreshDatabase;

    private function setupAgency(): array
    {
        $user = User::factory()->create(['status' => 1]);
        $agency = Agency::create(['user_id' => $user->id, 'name' => 'Đại lý A', 'commission_basis_points' => 1000]);
        $user->userRole()->syncWithoutDetaching([Role::where('name', 'dai-ly-du-lich')->firstOrFail()->id]);
        $tour = Tour::create(['t_title' => 'Tour A', 't_status' => 1, 't_price_adults' => 1000, 't_price_children' => 500, 't_duration_days' => 2]);
        $tour->forceFill(['agency_id' => $agency->id, 'cancellation_policy' => 'Hoàn theo chính sách A'])->save();
        $schedule = new TourSchedule;
        $schedule->forceFill(['ts_tour_id' => $tour->id, 'ts_start_date' => now()->addDays(5)->toDateString(), 'ts_end_date' => now()->addDays(6)->toDateString(), 'registration_deadline' => now()->addDays(4)->toDateString(), 'ts_number_guests' => 2, 'ts_status' => 1, 'adult_price' => 2000, 'child_price' => 1000])->save();

        return [$user, $agency, $tour, $schedule];
    }

    private function book(Tour $tour, TourSchedule $schedule): BookTour
    {
        return app(BookingService::class)->createForTour($tour->id, User::factory()->create(), ['b_name' => 'Khách', 'b_email' => 'guest@example.com', 'b_phone' => '0901234567', 'b_address' => 'HCM', 'b_start_date' => $schedule->ts_start_date, 'b_number_adults' => 1, 'b_number_children' => 0, 'b_number_child6' => 0, 'b_number_child2' => 0])['book'];
    }

    public function test_portal_denies_customers_and_scopes_every_resource(): void
    {
        [$user,$agency,$tour,$schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $this->get('/admin/agency')->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create(['status' => 1]), 'admins')->get('/admin/agency')->assertForbidden();
        [$other] = $this->setupAgency();
        $this->actingAs($other, 'admins')->get(route('agency.bookings.index'))->assertOk()->assertDontSee($booking->display_code);
        $this->get(route('agency.tours.index'))->assertOk()->assertDontSee(route('agency.tour.form.edit', $tour->id), false);
        $this->post('/admin/agency/tours/'.$tour->id, [])->assertNotFound();
        $this->post('/admin/agency/bookings/'.$booking->id.'/status', ['status' => 2])->assertNotFound();
        $this->post('/admin/agency/bookings/'.$booking->id.'/passengers', [])->assertNotFound();
        $this->post('/admin/agency/bookings/'.$booking->id.'/transactions', [])->assertNotFound();
        $this->post('/admin/agency/tours/'.$tour->id.'/schedules', [])->assertNotFound();
        $this->actingAs($user, 'admins')->get(route('agency.bookings.index'))->assertOk()->assertSee($booking->display_code);
        $this->get(route('agency.dashboard'))->assertOk()->assertDontSee($booking->display_code);
    }

    public function test_agency_sections_are_separate_pages_with_scoped_filters(): void
    {
        [$user, , $tour, $schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $this->actingAs($user, 'admins');

        $this->get(route('agency.profile.show'))->assertOk()
            ->assertSee('Thông tin đăng nhập của tôi')
            ->assertSee('Hồ sơ đại lý hiển thị với khách hàng')
            ->assertDontSee($booking->display_code);
        $this->get(route('agency.dashboard'))->assertOk()
            ->assertSee('Doanh thu ghi nhận')
            ->assertDontSee($booking->display_code);
        $this->get(route('agency.tours.index'))->assertOk()
            ->assertSee($tour->t_title)
            ->assertSee('Thêm đợt khởi hành')
            ->assertDontSee($booking->display_code);
        $this->get(route('agency.bookings.index', ['tour_id' => $tour->id]))->assertOk()
            ->assertSee($booking->display_code)
            ->assertSee('Tìm đăng ký');
        $this->get(route('agency.bookings.index', ['q' => 'khong-co-khach']))
            ->assertOk()->assertDontSee($booking->display_code);
    }

    public function test_booking_snapshots_schedule_price_policy_and_commission_and_checks_capacity(): void
    {
        [$user,$agency,$tour,$schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $tour->forceFill(['cancellation_policy' => 'Chính sách B', 't_price_adults' => 9000])->save();
        $schedule->forceFill(['adult_price' => 9000])->save();
        $this->assertEquals(2000, $booking->fresh()->total_price);
        $this->assertEquals('Hoàn theo chính sách A', $booking->fresh()->policy_snapshot);
        $this->assertEquals(1000, $booking->fresh()->commission_basis_points);
        $this->book($tour, $schedule);
        $this->expectException(\DomainException::class);
        $this->book($tour, $schedule);
    }

    public function test_finance_prevents_overpayment_overrefund_and_duplicate_reference(): void
    {
        [$user,$agency,$tour,$schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $this->actingAs($user, 'admins');
        $payload = ['type' => 'receipt', 'amount' => 2001, 'reference' => 'R1', 'note' => 'Chuyển khoản', 'occurred_at' => now()->toDateTimeString()];
        $url = '/admin/agency/bookings/'.$booking->id.'/transactions';
        $this->post($url, $payload)->assertSessionHasErrors();
        $payload['amount'] = 2000;
        $this->post($url, $payload)->assertSessionHasNoErrors();
        $this->post($url, $payload)->assertSessionHasErrors();
        $this->post('/admin/agency/bookings/'.$booking->id.'/status', ['status' => 5, 'note' => 'Khách yêu cầu hủy'])->assertSessionHasNoErrors();
        $payload['type'] = 'refund';
        $payload['reference'] = 'F1';
        $payload['amount'] = 2001;
        $this->post($url, $payload)->assertSessionHasErrors();
        $payload['amount'] = 2000;
        $this->post($url, $payload)->assertSessionHasNoErrors();
        $this->assertEquals(2, AgencyTransaction::count());
    }

    public function test_completion_and_attendance_require_payment_and_finished_trip(): void
    {
        [$user,$agency,$tour,$schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $this->actingAs($user, 'admins');
        $url = '/admin/agency/bookings/'.$booking->id;
        $this->post($url.'/status', ['status' => 2])->assertSessionHasNoErrors();
        $this->post($url.'/status', ['status' => 3])->assertSessionHasErrors();
        $this->post($url.'/transactions', ['type' => 'receipt', 'amount' => 2000, 'reference' => 'R2', 'note' => 'Đã nhận', 'occurred_at' => now()->toDateTimeString()])->assertSessionHasNoErrors();
        $this->post($url.'/status', ['status' => 3])->assertSessionHasNoErrors();
        $this->post($url.'/status', ['status' => 4])->assertSessionHasErrors();
        $booking->b_end_date = now()->subDay();
        $booking->save();
        $this->post($url.'/status', ['status' => 4])->assertSessionHasNoErrors();
        $this->assertNotNull($booking->fresh()->revenue_recognized_at);
        $this->post($url.'/passengers', ['name' => 'Khách', 'attendance' => 'completed'])->assertSessionHasNoErrors();
        $this->post($url.'/passengers', ['name' => 'Thừa', 'attendance' => 'completed'])->assertSessionHasErrors();
        $this->get('/admin/agency')->assertOk()->assertViewHas('metrics', fn ($m) => $m['Doanh thu ghi nhận'] == 2000 && $m['Phí nền tảng'] == 200 && $m['Đại lý được hưởng'] == 1800);
    }

    public function test_schedule_cannot_reduce_capacity_or_move_booked_dates(): void
    {
        [$user,$agency,$tour,$schedule] = $this->setupAgency();
        $this->book($tour, $schedule);
        $this->book($tour, $schedule);
        $this->actingAs($user, 'admins');
        $data = $schedule->only(['ts_start_date', 'ts_end_date', 'registration_deadline', 'ts_number_guests', 'adult_price', 'child_price', 'ts_status']);
        $data['ts_number_guests'] = 1;
        $this->post('/admin/agency/tours/'.$tour->id.'/schedules/'.$schedule->id, $data)->assertSessionHasErrors();
    }

    public function test_profile_and_draft_tour_cannot_override_ownership_or_verification(): void
    {
        [$user, $agency, $tour] = $this->setupAgency();
        $this->actingAs($user, 'admins');
        $this->post('/admin/agency/profile', ['name' => 'Tên mới', 'verification_information' => 'Giấy phép 123', 'verification_status' => 'verified', 'commission_basis_points' => 9999])->assertSessionHasNoErrors();
        $this->assertEquals('pending', $agency->fresh()->verification_status);
        $this->assertEquals(1000, $agency->fresh()->commission_basis_points);
        $data = ['t_title' => 'Tour mới', 't_price_adults' => 1000, 't_price_children' => 500, 't_duration_days' => 2, 't_status' => 3, 'cancellation_policy' => 'Chính sách mới', 'agency_id' => 9999];
        $this->post('/admin/agency/tours', $data)->assertSessionHasNoErrors();
        $created = Tour::where('t_title', 'Tour mới')->firstOrFail();
        $this->assertEquals($agency->id, $created->agency_id);
        $this->assertEquals('Chính sách mới', $created->cancellation_policy);
        $data['t_status'] = 1;
        $this->post('/admin/agency/tours/'.$created->id, $data)->assertSessionHasErrors('t_status');
        $agency->update(['active' => false]);
        $this->get('/admin/agency')->assertForbidden();
    }

    public function test_agency_uses_admin_tour_form_and_saves_its_fields_without_crossing_ownership(): void
    {
        [$user, $agency, $existing] = $this->setupAgency();
        [, , $foreignTour] = $this->setupAgency();
        $this->actingAs($user, 'admins');

        $this->get(route('agency.tour.form.create'))->assertOk()
            ->assertSee('Thông tin chương trình tour')
            ->assertSee('Lịch trình chi tiết')
            ->assertSee('Chính sách hủy / hoàn tiền')
            ->assertSee('Album ảnh')
            ->assertDontSee('Hướng dẫn viên phụ trách');
        $this->get(route('agency.tour.form.edit', $foreignTour->id))->assertNotFound();

        Storage::fake('uploads');
        $this->post(route('agency.tour.create'), [
            't_title' => 'Tour mới đủ thông tin', 't_location_id' => null, 't_type' => 'eco',
            't_journeys' => 'Huế - Đà Nẵng', 't_price_adults' => 1200000,
            't_price_children' => 600000, 't_sale' => 10,
            't_move_method' => 'Ô tô', 't_starting_gate' => 'Huế',
            't_duration_days' => 3, 't_duration_nights' => 2,
            't_content' => '<p>Khám phá</p>', 't_description' => '<p>Ngày đầu</p>',
            'cancellation_policy' => 'Hoàn 50% trước bảy ngày', 't_status' => 4,
            'activity_title' => ['Đi thuyền'], 'activity_icon' => ['fa fa-ship'],
            'activity_description' => ['Ngắm cảnh'],
            'images' => UploadedFile::fake()->image('cover.jpg'),
            'album_images' => [UploadedFile::fake()->image('album.jpg')],
            'agency_id' => 9999,
        ])->assertSessionHasNoErrors();

        $created = Tour::where('t_title', 'Tour mới đủ thông tin')->firstOrFail();
        $this->assertEquals($agency->id, $created->agency_id);
        $this->assertEquals('eco', $created->t_type);
        $this->assertEquals(10, $created->t_sale);
        $this->assertEquals('3 ngày 2 đêm', $created->t_schedule);
        $this->assertEquals('Đi thuyền', $created->t_activities[0]['title']);
        $this->assertNotEmpty($created->t_image);
        $this->assertCount(1, $created->t_anbum_image);
        $this->get(route('agency.tour.form.edit', $created->id))->assertOk()->assertSee('Huế - Đà Nẵng');
        $this->post(route('agency.tour.update', $foreignTour->id), [])->assertNotFound();
    }

    public function test_agency_staff_can_update_own_account_without_changing_access(): void
    {
        [$user, $agency] = $this->setupAgency();
        $this->actingAs($user, 'admins')->post(route('agency.account'), [
            'account_name' => 'Nhân sự mới', 'account_email' => 'agency-staff@example.com',
            'account_phone' => '0901234567', 'status' => 0, 'agency_id' => 9999,
        ])->assertSessionHasNoErrors();

        $this->assertSame('Nhân sự mới', $user->fresh()->name);
        $this->assertSame('agency-staff@example.com', $user->fresh()->email);
        $this->assertEquals(1, $user->fresh()->status);
        $this->assertEquals($agency->id, $user->fresh()->agency->id);
        $this->get(route('agency.dashboard'))->assertOk();
    }

    public function test_report_uses_transaction_date_and_excludes_other_agencies(): void
    {
        [$user, $agency, $tour, $schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        [, , $foreignTour, $foreignSchedule] = $this->setupAgency();
        $foreign = $this->book($foreignTour, $foreignSchedule);
        foreach ([$booking, $foreign] as $item) {
            AgencyTransaction::create(['book_tour_id' => $item->id, 'recorded_by' => $user->id, 'type' => 'receipt', 'amount' => 500, 'reference' => 'DATE-'.$item->id, 'note' => 'Thu cũ', 'occurred_at' => now()->subMonthNoOverflow()]);
        }
        $this->actingAs($user, 'admins')->get('/admin/agency?from='.today()->toDateString().'&to='.today()->toDateString())
            ->assertOk()->assertViewHas('metrics', fn ($m) => $m['Tiền đã thu'] == 0 && $m['Lượt đăng ký'] == 1);
        $this->get('/admin/agency')->assertOk()->assertViewHas('metrics', fn ($m) => $m['Tiền đã thu'] == 500);
        $lastMonth = now()->subMonthNoOverflow();
        $this->get('/admin/agency?month='.$lastMonth->month.'&year='.$lastMonth->year)
            ->assertOk()->assertViewHas('metrics', fn ($m) => $m['Tiền đã thu'] == 500);
    }

    public function test_new_schedule_and_customer_checkout_show_departure_prices(): void
    {
        [$user, $agency, $tour, $schedule] = $this->setupAgency();
        $data = ['ts_start_date' => now()->addDays(10)->toDateString(), 'ts_end_date' => now()->addDays(12)->toDateString(), 'registration_deadline' => now()->addDays(9)->toDateString(), 'adult_price' => 123456, 'child_price' => 60000, 'ts_number_guests' => 10, 'ts_status' => 1];
        $this->actingAs($user, 'admins')->post('/admin/agency/tours/'.$tour->id.'/schedules', $data)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tour_schedules', ['ts_tour_id' => $tour->id, 'adult_price' => 123456]);
        $this->post('/admin/agency/tours/'.$tour->id.'/schedules', $data)->assertSessionHasErrors();
        $this->actingAs(User::factory()->create(['status' => 1]), 'users')
            ->get(route('book.tour', [$tour->id, 'tour-a']))
            ->assertOk()->assertSee('123,456')->assertSee('Chính sách hủy / hoàn');
    }

    public function test_admin_grants_and_revokes_agency_staff_access(): void
    {
        $admin = User::factory()->create(['status' => 1]);
        $fullAccess = Permission::firstOrCreate(['name' => 'full-quyen-quan-ly'], ['display_name' => 'Toàn quyền']);
        $adminRole = Role::firstOrCreate(['name' => 'super-admin'], ['display_name' => 'Super Admin']);
        $adminRole->permissionRole()->syncWithoutDetaching([$fullAccess->id]);
        $admin->userRole()->syncWithoutDetaching([$adminRole->id]);
        $agencyRole = Role::where('name', 'dai-ly-du-lich')->firstOrFail();

        $this->actingAs($admin, 'admins')->post(route('user.create'), [
            'name' => 'Nhân viên A', 'email' => 'staff-agency@example.com',
            'password' => 'secret123', 'phone' => '0901234567',
            'status' => 1, 'role' => $agencyRole->id,
            'agency_name' => 'Đại lý Sao Mai',
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $staff = User::where('email', 'staff-agency@example.com')->firstOrFail();
        $this->assertTrue($staff->can('quan-ly-dai-ly'));
        $this->assertFalse($staff->can('xem-dashboard'));
        $this->assertDatabaseHas('agencies', ['user_id' => $staff->id, 'name' => 'Đại lý Sao Mai']);

        $this->actingAs($staff, 'admins')->get(route('agency.dashboard'))->assertOk();
        $this->get(route('admin.home'))->assertForbidden();

        $otherRole = Role::firstOrCreate(['name' => 'nhan-vien-thuong'], ['display_name' => 'Nhân viên thường']);
        $this->actingAs($admin, 'admins')->post(route('user.update', $staff->id), [
            'name' => 'Nhân viên A', 'email' => $staff->email,
            'phone' => '0901234567', 'status' => 1, 'role' => $otherRole->id,
        ])->assertSessionHas('success');
        $this->actingAs($staff->fresh(), 'admins')->get(route('agency.dashboard'))->assertForbidden();
    }

    public function test_agency_staff_login_opens_admin_agency_workspace(): void
    {
        $user = User::factory()->create(['email' => 'agency-login@example.com', 'password' => Hash::make('secret123'), 'status' => 1]);
        Agency::create(['user_id' => $user->id, 'name' => 'Đại lý Login']);
        $user->userRole()->syncWithoutDetaching([Role::where('name', 'dai-ly-du-lich')->firstOrFail()->id]);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret123'])
            ->assertRedirect(route('agency.dashboard'));
        $this->assertAuthenticatedAs($user, 'admins');
        $this->get('/agency')->assertRedirect('/admin/agency');
    }
}
