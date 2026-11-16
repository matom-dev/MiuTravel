<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\User;
use App\Models\BookTour;
use App\Services\BookingService;
use App\Services\TourScheduleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class FixedDepartureTest extends TestCase
{
    use RefreshDatabase;

    private function departure(): TourSchedule
    {
        Queue::fake();
        $tour = Tour::create(['t_title'=>'Tour cố định', 't_status'=>1, 't_price_adults'=>1000, 't_price_children'=>500]);
        return app(TourScheduleService::class)->save($tour, [
            'ts_start_date'=>today()->addDays(5)->toDateString(), 'ts_end_date'=>today()->addDays(7)->toDateString(),
            'registration_deadline'=>today()->addDays(4)->toDateString(), 'ts_number_guests'=>2,
            'adult_price'=>2000, 'child_price'=>800, 'ts_status'=>1,
        ]);
    }

    private function book(TourSchedule $schedule, array $data = []): BookTour
    {
        return app(BookingService::class)->createForTour($schedule->ts_tour_id, User::factory()->create(), array_merge([
            'b_start_date'=>$schedule->ts_start_date, 'b_name'=>'Khách', 'b_phone'=>'0901234567',
            'b_email'=>'guest@example.com', 'b_address'=>'HCM', 'b_number_adults'=>1, 'b_number_children'=>0,
        ], $data))['book'];
    }

    public function test_admin_tour_uses_fixed_dates_server_price_and_counts_infants(): void
    {
        $schedule = $this->departure();
        $book = $this->book($schedule, ['b_number_child2'=>1, 'b_price_adults'=>1]);
        $this->assertEquals($schedule->id, $book->b_tour_schedule_id);
        $this->assertEquals(2000, $book->b_price_adults);
        $this->assertEquals(substr($schedule->ts_end_date,0,10), $book->b_end_date->toDateString());
        $this->assertSame(0, $schedule->remaining_seats);
        $this->expectException(\DomainException::class);
        $this->book($schedule);
    }

    public function test_cancel_returns_seats(): void
    {
        $schedule = $this->departure();
        $book = $this->book($schedule, ['b_number_adults'=>2]);
        app(BookingService::class)->changeStatus($book, BookTour::STATUS_CANCELLED, 'Hủy', $book->user, 'users');
        $this->assertSame(2, $schedule->remaining_seats);
        $this->assertNotNull($this->book($schedule)->id);
    }

    public function test_rejects_unlisted_date(): void
    {
        $schedule = $this->departure();
        $this->expectException(\DomainException::class);
        $this->book($schedule, ['b_start_date'=>today()->addDays(6)->toDateString()]);
    }

    public function test_rejects_closed_expired_and_past_departures(): void
    {
        foreach ([['ts_status'=>0], ['registration_deadline'=>today()->subDay()], ['ts_start_date'=>today()]] as $changes) {
            $schedule = $this->departure();
            $schedule->forceFill($changes)->save();
            try {
                $this->book($schedule);
                $this->fail('Đợt không hợp lệ đã nhận đặt.');
            } catch (\DomainException $e) {
                $this->assertStringContainsString('không có đợt', $e->getMessage());
            }
        }
    }

    public function test_capacity_and_dates_are_protected_after_booking(): void
    {
        $schedule = $this->departure();
        $this->book($schedule, ['b_number_adults'=>2]);
        foreach ([['ts_number_guests'=>1], ['ts_start_date'=>today()->addDays(6)->toDateString()]] as $changes) {
            try {
                app(TourScheduleService::class)->save($schedule->tour, array_merge($schedule->only(array_keys(TourScheduleService::rules())), $changes), $schedule->id);
                $this->fail('Đã cho phép thay đổi đợt không hợp lệ.');
            } catch (ValidationException $e) {
                $this->assertNotEmpty($e->errors());
            }
        }
    }

    public function test_admin_can_manage_departures_and_cannot_edit_other_tour_schedule(): void
    {
        $schedule = $this->departure();
        $admin = User::factory()->create(['status'=>1]);
        $role = \App\Models\Role::create(['name'=>'fixed-tour-admin', 'display_name'=>'Admin']);
        $permission = \App\Models\Permission::firstOrCreate(['name'=>'quan-ly-tour'], ['display_name'=>'Quản lý tour']);
        $role->permissionRole()->attach($permission->id);
        $admin->userRole()->attach($role->id);
        $data = $schedule->only(array_keys(TourScheduleService::rules()));
        $this->actingAs($admin, 'admins')->get(route('tour.update', $schedule->ts_tour_id))
            ->assertOk()->assertSee('Thêm đợt khởi hành');
        $this->post(route('admin.tour.schedule.save', [$schedule->ts_tour_id, $schedule->id]),
            array_merge($data, ['ts_number_guests'=>3]))->assertSessionHas('success');
        $this->assertEquals(3, $schedule->fresh()->ts_number_guests);
        $other = $this->departure();
        $this->post(route('admin.tour.schedule.save', [$other->ts_tour_id, $schedule->id]), $data)->assertNotFound();
        $this->actingAs(User::factory()->create(['status'=>1]), 'admins')
            ->post(route('admin.tour.schedule.save', $schedule->ts_tour_id), $data)->assertForbidden();
    }

    public function test_legacy_reconciliation_is_read_only_by_default_and_preserves_prices(): void
    {
        $schedule = $this->departure();
        $book = $this->book($schedule);
        $book->b_tour_schedule_id = null;
        $book->save();
        $this->artisan('tours:reconcile-departures')->assertSuccessful();
        $this->assertNull($book->fresh()->b_tour_schedule_id);
        try {
            $this->book($schedule);
            $this->fail('Đợt chưa đối soát đã nhận khách mới.');
        } catch (\DomainException $e) {
            $this->assertStringContainsString('đối soát', $e->getMessage());
        }
        $this->artisan('tours:reconcile-departures', ['--apply'=>true])->assertSuccessful();
        $this->assertEquals($schedule->id, $book->fresh()->b_tour_schedule_id);
        $this->assertEquals(2000, $book->fresh()->b_price_adults);
        $this->assertNotNull($this->book($schedule)->id);
    }

    public function test_low_seat_warning_is_only_on_departure_rows_in_detail(): void
    {
        $schedule = $this->departure();
        $tour = $schedule->tour;
        $render = fn () => $this->get(route('tour.detail', [$tour->id, 'tour']))->assertOk()->getContent();
        $this->assertStringNotContainsString('Sắp hết chỗ', $render());
        $schedule->forceFill(['ts_number_guests'=>10])->save();
        $this->book($schedule, ['b_number_adults'=>8]);
        $this->assertStringContainsString('Sắp hết chỗ', $render());
        $card = view('page.common.itemTour', ['tour'=>$tour])->render();
        foreach (['Sắp hết chỗ', 'Khởi hành gần nhất', 'Đợt này còn', 'Xem các ngày khởi hành khác'] as $text) {
            $this->assertStringNotContainsString($text, $card);
        }
        $schedule->forceFill(['ts_number_guests'=>40])->save();
        $this->book($schedule, ['b_number_adults'=>26]);
        $this->assertStringNotContainsString('Sắp hết chỗ', $render());
        $this->book($schedule, ['b_number_adults'=>1]);
        $this->assertStringContainsString('Sắp hết chỗ', $render());
        $schedule->forceFill(['registration_deadline'=>today()->subDay()->toDateString()])->save();
        $this->assertStringNotContainsString('Sắp hết chỗ', $render());
        $this->assertStringContainsString('Đã đóng đăng ký', $render());
        $schedule->forceFill(['registration_deadline'=>today()->addDays(4)->toDateString()])->save();
        $this->book($schedule, ['b_number_adults'=>5]);
        $this->assertStringNotContainsString('Sắp hết chỗ', $render());
        $this->assertStringContainsString('Hết chỗ', $render());
    }

    public function test_customer_form_and_detail_show_fixed_departures(): void
    {
        $schedule = $this->departure();
        $this->actingAs(User::factory()->create(), 'users')
            ->get(route('book.tour', [$schedule->ts_tour_id, 'tour']))->assertOk()
            ->assertSee('Còn 2/2 chỗ')->assertDontSee('type="date" name="b_start_date"', false);
        $this->get(route('tour.detail', [$schedule->ts_tour_id, 'tour']))->assertOk()->assertSee('Lịch khởi hành cố định');
    }
}
