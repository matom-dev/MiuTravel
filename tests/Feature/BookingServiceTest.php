<?php

namespace Tests\Feature;

use App\Models\AppNotification;
use App\Models\AdminAuditLog;
use App\Models\BookingStatusHistory;
use App\Models\BookTour;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tour;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_booking_and_holds_requested_seats(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour([
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_price_adults' => 1000000,
            't_price_children' => 500000,
            't_sale' => 10,
            't_follow' => 1,
        ]);
        $startDate = now()->addDays(5)->format('Y-m-d');

        $result = app(BookingService::class)->createForTour($tour->id, $user, $this->bookingPayload([
            'b_start_date' => $startDate,
            'b_number_adults' => 2,
            'b_number_children' => 1,
        ]));

        $this->assertInstanceOf(BookTour::class, $result['book']);
        $this->assertSame(1, (int) $result['book']->b_status);
        $this->assertSame($startDate, $result['book']->b_start_date->format('Y-m-d'));
        $this->assertSame(now()->addDays(7)->format('Y-m-d'), $result['book']->b_end_date->format('Y-m-d'));
        $this->assertSame(900000, (int) $result['book']->b_price_adults);
        $this->assertSame(450000, (int) $result['book']->b_price_children);
        $this->assertSame(4, (int) $tour->fresh()->t_follow);
        $notification = AppNotification::where('type', 'booking_created')->first();
        $this->assertNotNull($notification);
        $this->assertSame('admins', $notification->receiver_guard);
        $this->assertStringContainsString('vừa đặt tour theo ngày khởi hành mong muốn', $notification->message);
        $this->assertSame($startDate, $notification->data['start_date']);
        $this->assertSame(now()->addDays(7)->format('Y-m-d'), $notification->data['end_date']);
        $this->assertSame(3, $notification->data['total_guests']);
        $this->assertSame(2250000, (int) $notification->data['total_price']);
        $this->assertDatabaseHas('app_notifications', [
            'receiver_guard' => 'admins',
            'type' => 'booking_created',
        ]);
        $this->assertDatabaseHas('booking_status_histories', [
            'book_tour_id' => $result['book']->id,
            'old_status' => null,
            'new_status' => BookTour::STATUS_PENDING,
            'changed_by' => $user->id,
            'changed_guard' => 'users',
        ]);
    }

    public function test_it_calculates_end_date_from_legacy_schedule_text(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour([
            't_schedule' => '4 ngay 3 dem',
            't_duration_days' => null,
            't_duration_nights' => null,
        ]);
        $startDate = now()->addDays(10)->format('Y-m-d');

        $result = app(BookingService::class)->createForTour($tour->id, $user, $this->bookingPayload([
            'b_start_date' => $startDate,
        ]));

        $this->assertSame(now()->addDays(13)->format('Y-m-d'), $result['book']->b_end_date->format('Y-m-d'));
        $this->assertSame(4, $tour->effective_duration_days);
        $this->assertSame(3, $tour->effective_duration_nights);
    }

    public function test_authenticated_user_can_book_tour_through_page_route(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $tour = $this->createTour([
            't_duration_days' => 2,
            't_duration_nights' => 1,
        ]);
        $startDate = now()->addDays(3)->format('Y-m-d');

        $response = $this
            ->actingAs($user, 'users')
            ->post(route('post.book.tour', $tour->id), $this->bookingPayload([
                'b_start_date' => $startDate,
                'b_number_adults' => 1,
                'b_number_children' => 0,
            ]));

        $response
            ->assertRedirect(route('page.home'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('book_tours', [
            'b_tour_id' => $tour->id,
            'b_user_id' => $user->id,
            'b_start_date' => $startDate . ' 00:00:00',
            'b_end_date' => now()->addDays(4)->format('Y-m-d') . ' 23:59:59',
            'b_status' => 1,
        ]);
    }

    public function test_booking_form_explains_flexible_departure_and_confirmation_modal(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour([
            't_duration_days' => 3,
            't_duration_nights' => 2,
            't_schedule' => '3 ngày 2 đêm',
        ]);

        $this->actingAs($user, 'users')
            ->get(route('book.tour', ['id' => $tour->id, 'slug' => 'tour-da-nang']))
            ->assertOk()
            ->assertSee('Ngày khởi hành mong muốn')
            ->assertSee('Thời gian tour')
            ->assertSee('3 ngày 2 đêm')
            ->assertSee('Ngày về dự kiến')
            ->assertSee('Kiểm tra thông tin đặt tour')
            ->assertSee('Tổng số khách')
            ->assertSee('Tổng tiền')
            ->assertSee('Miu Travel sẽ liên hệ xác nhận lại lịch trình trước khi chốt booking.');
    }

    public function test_it_confirms_booking_and_moves_seats_from_follow_to_registered(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour([
            't_follow' => 3,
            't_number_registered' => 4,
        ]);
        $booking = $this->createBooking($tour, $user, [
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
            'b_number_adults' => 2,
            'b_number_children' => 1,
            'b_status' => 1,
        ]);

        $result = app(BookingService::class)->changeStatus($booking, BookTour::STATUS_CONFIRMED);

        $this->assertSame(BookTour::STATUS_CONFIRMED, (int) $result['bookTour']->b_status);
        $this->assertSame('email', $result['mail']['view']);
        $this->assertSame(0, (int) $tour->fresh()->t_follow);
        $this->assertSame(7, (int) $tour->fresh()->t_number_registered);
        $this->assertDatabaseHas('app_notifications', [
            'receiver_guard' => 'users',
            'receiver_id' => $user->id,
            'type' => 'booking_status_updated',
        ]);
        $notification = AppNotification::where('type', 'booking_status_updated')->first();
        $this->assertStringContainsString('theo ngày khởi hành mong muốn', $notification->message);
        $this->assertStringContainsString('ngày về dự kiến', $notification->message);
        $this->assertStringContainsString('Tổng tiền tạm tính', $notification->message);
        $this->assertSame($result['bookTour']->b_end_date->format('Y-m-d'), $notification->data['end_date']);
        $this->assertSame($result['bookTour']->total_price, (int) $notification->data['total_price']);
        $this->assertDatabaseHas('booking_status_histories', [
            'book_tour_id' => $booking->id,
            'old_status' => BookTour::STATUS_PENDING,
            'new_status' => BookTour::STATUS_CONFIRMED,
        ]);
    }

    public function test_booking_emails_show_flexible_departure_summary(): void
    {
        $user = User::factory()->create([
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
        ]);
        $tour = $this->createTour(['t_title' => 'Tour Da Nang linh hoat']);
        $bookTour = $this->createBooking($tour, $user, [
            'b_start_date' => '2026-09-10 00:00:00',
            'b_end_date' => '2026-09-12 23:59:59',
            'b_number_adults' => 1,
            'b_number_children' => 1,
            'b_number_child6' => 1,
            'b_number_child2' => 1,
        ]);

        $emailViews = [
            view('emailtn', ['book' => $bookTour, 'tour' => $tour, 'user' => $user])->render(),
            view('email', compact('bookTour', 'tour', 'user'))->render(),
            view('emailtt', compact('bookTour', 'tour', 'user'))->render(),
            view('emailhuy', compact('bookTour', 'tour', 'user'))->render(),
        ];

        foreach ($emailViews as $html) {
            $this->assertStringContainsString('Tour Da Nang linh hoat', $html);
            $this->assertStringContainsString('Ngày khởi hành mong muốn', $html);
            $this->assertStringContainsString('10/09/2026', $html);
            $this->assertStringContainsString('Ngày về dự kiến', $html);
            $this->assertStringContainsString('12/09/2026', $html);
            $this->assertStringContainsString('Số khách', $html);
            $this->assertStringContainsString('Tổng tiền tạm tính', $html);
            $this->assertStringContainsString('1.875.000', $html);
        }
    }

    public function test_it_cancels_pending_booking_and_releases_followed_seats(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour(['t_follow' => 2]);
        $booking = $this->createBooking($tour, $user, [
            'b_number_adults' => 2,
            'b_status' => 1,
        ]);

        $result = app(BookingService::class)->changeStatus($booking, BookTour::STATUS_CANCELLED);

        $this->assertSame(BookTour::STATUS_CANCELLED, (int) $result['bookTour']->b_status);
        $this->assertSame('emailhuy', $result['mail']['view']);
        $this->assertSame(0, (int) $tour->fresh()->t_follow);
    }

    public function test_it_supports_full_valid_booking_status_transition_flow(): void
    {
        $user = User::factory()->create();
        $tour = $this->createTour([
            't_follow' => 1,
            't_number_registered' => 0,
        ]);
        $booking = $this->createBooking($tour, $user, [
            'b_number_adults' => 1,
            'b_status' => BookTour::STATUS_PENDING,
        ]);

        app(BookingService::class)->changeStatus($booking, BookTour::STATUS_CONFIRMED);
        app(BookingService::class)->changeStatus($booking->fresh(), BookTour::STATUS_PAID);
        app(BookingService::class)->changeStatus($booking->fresh(), BookTour::STATUS_COMPLETED);

        $this->assertSame(BookTour::STATUS_COMPLETED, (int) $booking->fresh()->b_status);
        $this->assertSame([
            BookTour::STATUS_CONFIRMED,
            BookTour::STATUS_PAID,
            BookTour::STATUS_COMPLETED,
        ], BookingStatusHistory::where('book_tour_id', $booking->id)->whereNotNull('old_status')->pluck('new_status')->all());
    }

    public function test_it_prevents_overbooking_when_capacity_is_configured(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Tour không còn đủ chỗ cho số khách yêu cầu');

        $user = User::factory()->create();
        $tour = $this->createTour([
            't_number_guests' => 3,
            't_number_registered' => 1,
            't_follow' => 1,
        ]);

        app(BookingService::class)->createForTour($tour->id, $user, $this->bookingPayload([
            'b_number_adults' => 2,
        ]));
    }

    public function test_admin_can_filter_booking_by_customer_and_status_and_audit_status_change(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        $this->grantPermissions($admin, ['quan-ly-dat-tour', 'xoa-va-cap-nhat-trang-thai']);
        $user = User::factory()->create(['name' => 'Tran Customer', 'email' => 'customer@example.com', 'phone' => '0901234567']);
        $tour = $this->createTour(['t_follow' => 1]);
        $booking = $this->createBooking($tour, $user, [
            'b_name' => 'Tran Customer',
            'b_email' => 'customer@example.com',
            'b_phone' => '0901234567',
            'b_status' => BookTour::STATUS_PENDING,
        ]);
        $this->createBooking($tour, User::factory()->create(), [
            'b_name' => 'Other Guest',
            'b_status' => BookTour::STATUS_CANCELLED,
        ]);

        $this->actingAs($admin, 'admins')
            ->get(route('book.tour.index', [
                'customer' => 'Customer',
                'b_status' => BookTour::STATUS_PENDING,
            ]))
            ->assertOk()
            ->assertSee('Tran Customer')
            ->assertDontSee('Other Guest');

        $this->actingAs($admin, 'admins')
            ->patch(route('book.tour.update.status', [
                'status' => BookTour::STATUS_CONFIRMED,
                'id' => $booking->id,
            ]))
            ->assertRedirect(route('book.tour.index'));

        $this->assertDatabaseHas('admin_audit_logs', [
            'actor_id' => $admin->id,
            'action' => 'booking.status_updated',
            'subject_type' => BookTour::class,
            'subject_id' => $booking->id,
        ]);
    }

    public function test_it_rejects_invalid_status_transition(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Thao tác chuyển trạng thái không hợp lệ');

        $user = User::factory()->create();
        $tour = $this->createTour();
        $booking = $this->createBooking($tour, $user, ['b_status' => 1]);

        app(BookingService::class)->changeStatus($booking, BookTour::STATUS_PAID);
    }

    private function grantPermissions(User $user, array $permissionNames): void
    {
        $role = Role::create([
            'name' => 'booking-admin-' . $user->id,
            'display_name' => 'Booking admin',
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

    private function createTour(array $attributes = []): Tour
    {
        $location = Location::create([
            'l_name' => 'Da Nang',
            'l_slug' => 'da-nang',
            'l_status' => 1,
        ]);

        return Tour::create(array_merge([
            't_title' => 'Tour Da Nang',
            't_journeys' => 'Da Nang',
            't_schedule' => '3 ngay 2 dem',
            't_duration_days' => 3,
            't_duration_nights' => 2,
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
        return BookTour::create(array_merge($this->bookingPayload(), [
            'b_tour_id' => $tour->id,
            'b_user_id' => $user->id,
            'b_status' => 1,
            'b_price_adults' => $tour->t_price_adults,
            'b_price_children' => $tour->t_price_children,
            'b_price_child6' => 250000,
            'b_price_child2' => 125000,
        ], $attributes));
    }

    private function bookingPayload(array $attributes = []): array
    {
        return array_merge([
            'b_name' => 'Nguyen Van A',
            'b_email' => 'a@example.com',
            'b_phone' => '0900000000',
            'b_address' => 'Da Nang',
            'b_start_date' => now()->addDays(5)->format('Y-m-d'),
            'b_note' => null,
            'b_number_adults' => 1,
            'b_number_children' => 0,
            'b_number_child6' => 0,
            'b_number_child2' => 0,
        ], $attributes);
    }
}
