<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\HomeController;
use App\Models\BookTour;
use App\Models\Location;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminBookingOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_overview_filters_by_requested_departure_date(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour();

        $matchedBooking = $this->createBooking($tour, $user, [
            'b_name' => 'Khach ngay 10',
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
            'created_at' => '2026-08-01 08:00:00',
        ]);
        $this->createBooking($tour, $user, [
            'b_name' => 'Khach ngay 12',
            'b_start_date' => '2026-09-12 00:00:00',
            'b_end_date' => '2026-09-14 23:59:59',
            'created_at' => '2026-08-01 08:00:00',
        ]);

        $request = Request::create('/admin/booking-overview', 'GET', [
            'b_start_date' => '2026-09-10',
        ]);

        $response = app(HomeController::class)->bookingOverview($request);
        $bookings = $response->getData()['bookings'];

        $this->assertSame(1, $bookings->total());
        $this->assertSame($matchedBooking->id, $bookings->first()->id);
    }

    private function createTour(array $attributes = []): Tour
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        return Tour::create(array_merge([
            't_title' => 'Tour Phong Nha',
            't_journeys' => 'Dong Hoi - Phong Nha',
            't_schedule' => '3 ngày 2 đêm',
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_location_id' => $location->id,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_status' => 1,
        ], $attributes));
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
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
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
