<?php

namespace Tests\Feature;

use App\Models\BookTour;
use App\Models\Comment;
use App\Models\Location;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerReviewFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_customers_with_confirmed_booking_can_review_tour(): void
    {
        $tour = $this->createTour();
        $visitor = $this->createUser('visitor@example.test');

        $this->actingAs($visitor, 'users')
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->postJson(route('comment'), [
                'tour_id' => $tour->id,
                'rating' => 5,
                'message' => 'Tour rat dang trai nghiem.',
            ])
            ->assertForbidden()
            ->assertJson(['code' => 403]);

        $customer = $this->createUser('customer@example.test');
        BookTour::create([
            'b_code' => 'MT-2026-000001',
            'b_tour_id' => $tour->id,
            'b_user_id' => $customer->id,
            'b_name' => $customer->name,
            'b_email' => $customer->email,
            'b_phone' => '0901234567',
            'b_address' => 'Dong Hoi',
            'b_number_adults' => 2,
            'b_number_children' => 0,
            'b_number_child6' => 0,
            'b_number_child2' => 0,
            'b_price_adults' => 1000000,
            'b_price_children' => 500000,
            'b_price_child6' => 250000,
            'b_price_child2' => 100000,
            'b_status' => BookTour::STATUS_CONFIRMED,
        ]);

        $this->actingAs($customer, 'users')
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->postJson(route('comment'), [
                'tour_id' => $tour->id,
                'rating' => 4,
                'message' => 'Lich trinh ro rang, huong dan vien nhiet tinh.',
            ])
            ->assertOk()
            ->assertJson(['code' => 200]);

        $this->assertDatabaseHas('comments', [
            'cm_tour_id' => $tour->id,
            'cm_user_id' => $customer->id,
            'cm_rating' => 4,
            'cm_status' => Comment::STATUS_PENDING,
        ]);

        $this->get(route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]))
            ->assertOk()
            ->assertDontSee('Lich trinh ro rang');

        Comment::query()->where('cm_tour_id', $tour->id)->update([
            'cm_status' => Comment::STATUS_APPROVED,
        ]);

        $this->get(route('tour.detail', ['id' => $tour->id, 'slug' => safeTitle($tour->t_title)]))
            ->assertOk()
            ->assertSee('Lich trinh ro rang')
            ->assertSee('4 sao');
    }

    private function createUser(string $email): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt('password'),
        ]);
    }

    private function createTour(): Tour
    {
        $location = Location::create([
            'l_name' => 'Quang Binh',
            'l_slug' => 'quang-binh',
            'l_status' => 1,
        ]);

        return Tour::create([
            't_title' => 'Tour review Phong Nha',
            't_journeys' => 'Quang Binh',
            't_schedule' => '1 ngay',
            't_duration_days' => 1,
            't_duration_nights' => 0,
            't_move_method' => 'Oto',
            't_starting_gate' => 'Dong Hoi',
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_location_id' => $location->id,
            't_status' => 1,
        ]);
    }
}
