<?php

namespace Tests\Feature;

use App\Models\BookTour;
use App\Models\Location;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerAccountTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_track_flexible_tour_booking_details(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour();

        $pendingBooking = $this->createBooking($tour, $user, [
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
            'b_number_adults' => 2,
            'b_number_children' => 1,
            'b_number_child6' => 1,
            'b_number_child2' => 0,
            'b_price_adults' => 1000000,
            'b_price_children' => 500000,
            'b_price_child6' => 250000,
            'b_price_child2' => 125000,
            'b_status' => 1,
        ]);
        $confirmedBooking = $this->createBooking($tour, $user, [
            'b_start_date' => '2026-10-01 00:00:00',
            'b_end_date' => '2026-10-03 23:59:59',
            'b_status' => 2,
        ]);

        $response = $this->actingAs($user, 'users')->get(route('my.tour'));

        $response
            ->assertOk()
            ->assertSee('Ngày đi mong muốn')
            ->assertSee('10/09/2026')
            ->assertSee('Ngày về dự kiến')
            ->assertSee('12/09/2026')
            ->assertSee('Tổng khách: 4')
            ->assertSee('2.750.000')
            ->assertSee($pendingBooking->display_code)
            ->assertSee('Tải phiếu xác nhận')
            ->assertSee('Chờ xác nhận')
            ->assertSee('Đã xác nhận')
            ->assertDontSee('Timeline trạng thái')
            ->assertSee('Lịch trình sẽ được Miu Travel xác nhận trước khi chốt booking.')
            ->assertSee(route('post.cancel.order.tour', $pendingBooking->id), false)
            ->assertSee(route('my.tour.confirmation', $pendingBooking->id), false)
            ->assertDontSee(route('post.cancel.order.tour', $confirmedBooking->id), false);
    }

    public function test_customer_can_cancel_only_pending_booking(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $tour = $this->createTour(['t_follow' => 1, 't_number_registered' => 1]);
        $pendingBooking = $this->createBooking($tour, $user, [
            'b_number_adults' => 1,
            'b_status' => 1,
        ]);
        $confirmedBooking = $this->createBooking($tour, $user, [
            'b_number_adults' => 1,
            'b_status' => 2,
        ]);

        $this->actingAs($user, 'users')
            ->post(route('post.cancel.order.tour', $pendingBooking->id), [
                'cancel_reason' => 'Gia đình đổi lịch đi',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(5, (int) $pendingBooking->fresh()->b_status);
        $this->assertSame('Gia đình đổi lịch đi', $pendingBooking->fresh()->b_cancel_reason);

        $this->actingAs($user, 'users')
            ->post(route('post.cancel.order.tour', $confirmedBooking->id))
            ->assertRedirect()
            ->assertSessionHas('error', 'Chỉ có thể hủy booking đang chờ xác nhận');

        $this->assertSame(2, (int) $confirmedBooking->fresh()->b_status);
    }

    public function test_customer_can_download_own_booking_confirmation_pdf(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $tour = $this->createTour();
        $booking = $this->createBooking($tour, $user);
        $otherBooking = $this->createBooking($tour, $otherUser);

        $this->actingAs($user, 'users')
            ->get(route('my.tour.confirmation', $booking->id))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($user, 'users')
            ->get(route('my.tour.confirmation', $otherBooking->id))
            ->assertNotFound();
    }

    private function createTour(array $attributes = []): Tour
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        return Tour::create(array_merge([
            't_title' => 'Tour Phong Nha linh hoat',
            't_journeys' => 'Dong Hoi - Phong Nha',
            't_schedule' => '3 ngày 2 đêm',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_move_method' => 'Ô tô',
            't_starting_gate' => 'Đồng Hới',
            't_location_id' => $location->id,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_sale' => 0,
            't_number_registered' => 0,
            't_follow' => 0,
            't_status' => 1,
        ], $attributes));
    }

    private function createBooking(Tour $tour, User $user, array $attributes = []): BookTour
    {
        return BookTour::create(array_merge([
            'b_tour_id' => $tour->id,
            'b_user_id' => $user->id,
            'b_name' => $user->name,
            'b_email' => $user->email,
            'b_phone' => '0900000000',
            'b_address' => 'Quang Binh',
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
            'b_note' => null,
            'b_number_adults' => 1,
            'b_number_children' => 0,
            'b_number_child6' => 0,
            'b_number_child2' => 0,
            'b_price_adults' => 1000000,
            'b_price_children' => 500000,
            'b_price_child6' => 250000,
            'b_price_child2' => 125000,
            'b_status' => 1,
        ], $attributes));
    }
}
