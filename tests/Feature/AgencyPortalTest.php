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

    public function test_monthly_revenue_chart_scopes_agency_year_status_and_filters(): void
    {
        [$user, , $tour, $schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $booking->forceFill(['b_status'=>BookTour::STATUS_COMPLETED, 'revenue_recognized_at'=>'2025-03-15 12:00:00', 'commission_basis_points'=>1250])->save();
        [$other, , $otherTour, $otherSchedule] = $this->setupAgency();
        $foreignBooking = $this->book($otherTour, $otherSchedule);
        $foreignBooking->forceFill(['b_status'=>BookTour::STATUS_COMPLETED, 'revenue_recognized_at'=>'2025-03-15 12:00:00'])->save();
        $uncompleted = $this->book($tour, $schedule);
        $uncompleted->forceFill(['revenue_recognized_at'=>'2025-03-15 12:00:00'])->save();
        $this->actingAs($user, 'admins')->get(route('agency.dashboard', ['year'=>2025, 'chart_type'=>'revenue']))
            ->assertOk()->assertSee('Doanh thu ghi nhận theo tháng')->assertSee('agency-revenue-chart')
            ->assertViewHas('revenueChart', function ($chart) {
                return $chart['year'] === 2025 && count($chart['revenue']) === 12
                    && $chart['revenue'][2] == 2000 && array_sum($chart['revenue']) == 2000
                    && $chart['commission'][2] == 250 && $chart['net'][2] == 1750;
            })->assertViewHas('metrics', fn ($metrics) => $metrics['Doanh thu ghi nhận'] == 2000);
        $this->get(route('agency.dashboard', ['year'=>2025, 'month'=>4]))->assertOk()
            ->assertViewHas('revenueChart', fn ($chart) => array_sum($chart['revenue']) == 0);
        $this->get(route('agency.dashboard', ['year'=>2025, 'tour_id'=>$otherTour->id]))->assertOk()
            ->assertViewHas('revenueChart', fn ($chart) => array_sum($chart['revenue']) == 0);
        $this->get(route('agency.dashboard'))->assertOk()
            ->assertViewHas('revenueChart', fn ($chart) => $chart['year'] === now()->year && array_sum($chart['revenue']) == 0);
        $this->get(route('agency.dashboard', ['year'=>1999]))->assertSessionHasErrors('year');
    }

    public function test_collected_chart_is_default_and_empty_chart_has_no_canvas(): void
    {
        [$user, , $tour, $schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        $this->actingAs($user, 'admins')->get(route('agency.dashboard'))->assertOk()
            ->assertSee('Chưa có khoản thu hoặc hoàn tiền')->assertDontSee('<canvas', false);
        AgencyTransaction::create(['book_tour_id'=>$booking->id, 'recorded_by'=>$user->id, 'type'=>'receipt', 'amount'=>1500, 'note'=>'Chart test', 'reference'=>'CHART-RECEIPT', 'occurred_at'=>now()]);
        AgencyTransaction::create(['book_tour_id'=>$booking->id, 'recorded_by'=>$user->id, 'type'=>'refund', 'amount'=>200, 'note'=>'Chart test', 'reference'=>'CHART-REFUND', 'occurred_at'=>now()]);
        [$other, , $foreignTour, $foreignSchedule] = $this->setupAgency();
        $foreignBooking = $this->book($foreignTour, $foreignSchedule);
        AgencyTransaction::create(['book_tour_id'=>$foreignBooking->id, 'recorded_by'=>$other->id, 'type'=>'receipt', 'amount'=>9999, 'note'=>'Chart test', 'reference'=>'CHART-FOREIGN', 'occurred_at'=>now()]);
        $this->get(route('agency.dashboard'))->assertOk()->assertSee('Tiền đã thu theo tháng')->assertSee('<canvas', false)
            ->assertViewHas('revenueChart', fn ($chart) => $chart['type']==='collected' && array_sum($chart['collected'])==1500 && array_sum($chart['refunded'])==200 && array_sum($chart['cash_net'])==1300 && array_sum($chart['revenue'])==0);
        $this->get(route('agency.dashboard', ['chart_type'=>'revenue']))->assertOk()
            ->assertSee('Chưa có doanh thu ghi nhận')->assertDontSee('<canvas', false);
        $this->get(route('agency.dashboard', ['chart_type'=>'invalid']))->assertSessionHasErrors('chart_type');
    }

    public function test_global_fee_is_admin_editable_and_only_changes_new_bookings(): void
    {
        [$user, , $tour, $schedule] = $this->setupAgency();
        $old = $this->book($tour, $schedule);
        $this->assertEquals(1500, $old->commission_basis_points);
        $this->actingAs($user, 'admins')->post(route('admin.platform-fee.update'), ['percentage'=>1])->assertForbidden();
        $admin = User::factory()->create(['status'=>1]);
        $role = Role::create(['name'=>'fee-admin', 'display_name'=>'Fee admin']);
        $permission = Permission::firstOrCreate(['name'=>'full-quyen-quan-ly'], ['display_name'=>'Full']);
        $role->permissionRole()->attach($permission->id);
        $admin->userRole()->attach($role->id);
        $this->actingAs($admin, 'admins')->get(route('admin.platform-fee.edit'))->assertOk()->assertSee('15');
        $this->post(route('admin.platform-fee.update'), ['percentage'=>'12.25'])->assertSessionHas('success');
        $new = $this->book($tour, $schedule);
        $this->assertEquals(1225, $new->commission_basis_points);
        $this->assertEquals(1500, $old->fresh()->commission_basis_points);
        $this->assertEquals(2000, $new->total_price);
        $this->assertDatabaseHas('admin_audit_logs', ['action'=>'platform_fee.updated']);
        foreach ([-1, 101, '1.234'] as $invalid) {
            $this->post(route('admin.platform-fee.update'), ['percentage'=>$invalid])->assertSessionHasErrors('percentage');
        }
    }

    public function test_agency_can_add_departure_dates_and_capacity_from_edit_page(): void
    {
        [$user, , $tour, $schedule] = $this->setupAgency();
        $url = route('agency.tour.form.edit', $tour->id);
        $this->actingAs($user, 'admins')->get($url)->assertOk()
            ->assertSee('Ngày đi, ngày về và số chỗ')
            ->assertSee('name="ts_start_date"', false)->assertSee('name="ts_end_date"', false)
            ->assertSee('name="ts_number_guests"', false)->assertSee(route('agency.schedule.create', $tour->id), false);
        $data = ['ts_start_date'=>today()->addDays(15)->toDateString(), 'ts_end_date'=>today()->addDays(17)->toDateString(),
            'registration_deadline'=>today()->addDays(14)->toDateString(), 'ts_number_guests'=>30,
            'adult_price'=>2000, 'child_price'=>1000, 'ts_status'=>1, 'update_assignments'=>1];
        $this->from($url)->post(route('agency.schedule.create', $tour->id), $data)->assertRedirect($url)->assertSessionHas('success');
        $this->assertDatabaseHas('tour_schedules', ['ts_tour_id'=>$tour->id, 'ts_start_date'=>$data['ts_start_date'], 'ts_end_date'=>$data['ts_end_date'], 'ts_number_guests'=>30]);
        $this->from($url)->post(route('agency.schedule.create', $tour->id), array_merge($data, ['ts_number_guests'=>0]))
            ->assertRedirect($url)->assertSessionHasErrors('ts_number_guests');
        $this->get($url)->assertOk()->assertSee('alert-danger', false);
    }

    public function test_admin_platform_fee_report_uses_booking_snapshots_and_recognition_period(): void
    {
        [$user, $agency, $tour, $schedule] = $this->setupAgency();
        $first = $this->book($tour, $schedule);
        $first->forceFill(['b_status'=>BookTour::STATUS_COMPLETED, 'revenue_recognized_at'=>'2025-03-01 12:00:00', 'commission_basis_points'=>1500])->save();
        $pending = $this->book($tour, $schedule);
        $pending->forceFill(['revenue_recognized_at'=>'2025-03-02 12:00:00'])->save();
        [$other, $otherAgency, $otherTour, $otherSchedule] = $this->setupAgency();
        $second = $this->book($otherTour, $otherSchedule);
        $second->forceFill(['b_status'=>BookTour::STATUS_COMPLETED, 'revenue_recognized_at'=>'2025-03-03 12:00:00', 'commission_basis_points'=>1225])->save();
        $cancelled = $this->book($otherTour, $otherSchedule);
        $cancelled->forceFill(['b_status'=>BookTour::STATUS_CANCELLED, 'revenue_recognized_at'=>'2025-03-04 12:00:00'])->save();
        $url = route('admin.platform-fee.report', ['year'=>2025, 'month'=>3]);
        $this->get($url)->assertRedirect(route('admin.login'));
        $this->actingAs($user, 'admins')->get($url)->assertForbidden();
        $admin = User::factory()->create(['status'=>1]);
        $role = Role::create(['name'=>'fee-report-reader', 'display_name'=>'Revenue reader']);
        $permission = Permission::firstOrCreate(['name'=>'xem-doanh-thu'], ['display_name'=>'Revenue']);
        $role->permissionRole()->attach($permission->id);
        $admin->userRole()->attach($role->id);
        $this->actingAs($admin, 'admins')->get($url)->assertOk()->assertSee('Phí nền tảng từ đại lý')
            ->assertSee($first->display_code)->assertSee($second->display_code)->assertDontSee($pending->display_code)->assertDontSee($cancelled->display_code)
            ->assertViewHas('totals', fn ($t) => $t['count']==2 && $t['revenue']==4000 && $t['fee']==545 && $t['net']==3455)
            ->assertViewHas('groups', fn ($g) => $g->count()===2);
        $this->get(route('admin.platform-fee.report', ['year'=>2025,'month'=>3,'agency_id'=>$agency->id]))->assertOk()
            ->assertViewHas('totals', fn ($t) => $t['count']==1 && $t['fee']==300)->assertDontSee($second->display_code);
        $this->get(route('admin.platform-fee.report', ['year'=>2025,'month'=>4]))->assertOk()
            ->assertViewHas('totals', fn ($t) => $t['count']==0 && $t['fee']==0)->assertSee('Chưa có phí nền tảng');
        $this->get(route('admin.platform-fee.report', ['year'=>2025,'month'=>'']))->assertOk()
            ->assertViewHas('totals', fn ($t) => $t['fee']==545);
        $this->get(route('admin.platform-fee.edit'))->assertForbidden();
        $this->get(route('admin.platform-fee.report', ['month'=>13]))->assertSessionHasErrors('month');
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
        $this->assertEquals(1500, $booking->fresh()->commission_basis_points);
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
        $this->get('/admin/agency')->assertOk()->assertViewHas('metrics', fn ($m) => $m['Doanh thu ghi nhận'] == 2000 && $m['Phí nền tảng'] == 300 && $m['Đại lý được hưởng'] == 1700);
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

    public function test_transferring_a_tour_does_not_transfer_booking_access_or_revenue(): void
    {
        [$owner, $originalAgency, $tour, $schedule] = $this->setupAgency();
        $booking = $this->book($tour, $schedule);
        [$newOwner, $newAgency] = $this->setupAgency();
        $tour->forceFill(['agency_id' => $newAgency->id])->save();
        $this->assertEquals($originalAgency->id, $booking->fresh()->agency_id);
        $this->actingAs($owner, 'admins')->get(route('agency.bookings.index'))->assertOk()->assertSee($booking->display_code);
        $this->get(route('agency.dashboard'))->assertOk()->assertViewHas('metrics', fn ($metrics) => $metrics['Giá trị đặt tour'] === 2000);
        $this->actingAs($newOwner, 'admins')->get(route('agency.bookings.index'))->assertOk()->assertDontSee($booking->display_code);
        $this->post(route('agency.booking.status', $booking->id), ['status' => 2])->assertNotFound();
        $this->post(route('agency.transaction', $booking->id), [])->assertNotFound();
    }

    public function test_schedule_guides_are_scoped_and_override_general_tour_assignments(): void
    {
        [$owner, , $tour, $schedule] = $this->setupAgency();
        $general = \App\Models\TourGuide::create(['tg_name' => 'General guide', 'tg_role' => 'guide', 'tg_status' => 1]);
        $own = \App\Models\TourGuide::create(['tg_name' => 'Departure guide', 'tg_role' => 'guide', 'tg_status' => 1, 'tg_user_id' => $owner->id]);
        $foreign = \App\Models\TourGuide::create(['tg_name' => 'Other agency guide', 'tg_role' => 'guide', 'tg_status' => 1]);
        $tour->staffGuides()->attach($general->id, ['tga_role' => 'guide']);
        $this->assertEquals($general->id, $schedule->fresh()->effective_guides->first()['guide']->id);
        $payload = [
            'ts_start_date' => substr($schedule->ts_start_date, 0, 10),
            'ts_end_date' => substr($schedule->ts_end_date, 0, 10),
            'registration_deadline' => substr($schedule->registration_deadline, 0, 10),
            'ts_number_guests' => 2, 'adult_price' => 2000, 'child_price' => 1000, 'ts_status' => 1,
            'update_assignments' => 1, 'guide_ids' => [$own->id],
        ];
        $url = route('agency.schedule.update', [$tour->id, $schedule->id]);
        $this->actingAs($owner, 'admins')->post($url, $payload)->assertSessionHasNoErrors();
        $this->assertEquals($own->id, $schedule->fresh()->effective_guides->first()['guide']->id);
        $this->get(route('agency.tours.index'))->assertOk()->assertSee('Departure guide')->assertDontSee('Other agency guide');
        $payload['guide_ids'] = [$foreign->id];
        $this->post($url, $payload)->assertSessionHasErrors();
        $this->assertEquals($own->id, $schedule->fresh()->effective_guides->first()['guide']->id);
        $payload['guide_ids'] = [];
        $this->post($url, $payload)->assertSessionHasNoErrors();
        $this->assertEquals($general->id, $schedule->fresh()->effective_guides->first()['guide']->id);
    }
}
