<?php

namespace Tests\Feature;

use App\Models\BookTour;
use App\Models\Tour;
use App\Models\User;
use App\Models\VnpayPayment;
use App\Services\VnpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class VnpayPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Bus::fake();
        config(['vnpay.tmn_code' => 'TESTCODE', 'vnpay.hash_secret' => 'test-secret']);
    }

    private function booking(int $status = BookTour::STATUS_CONFIRMED): BookTour
    {
        $user = User::factory()->create();
        $tour = Tour::create(['t_title' => 'Tour VNPay', 't_status' => 1]);
        return BookTour::create([
            'b_tour_id' => $tour->id, 'b_user_id' => $user->id, 'b_name' => $user->name,
            'b_email' => $user->email, 'b_phone' => '0901234567', 'b_address' => 'Hanoi',
            'b_number_children' => 0, 'b_number_child6' => 0, 'b_number_child2' => 0,
            'b_price_children' => 0, 'b_price_child6' => 0, 'b_price_child2' => 0,
            'b_status' => $status, 'b_number_adults' => 2, 'b_price_adults' => 100000,
            'b_start_date' => now()->addDays(5), 'b_end_date' => now()->addDays(6),
        ]);
    }

    private function signedResult(BookTour $book, array $overrides = []): array
    {
        app(VnpayService::class)->create($book->id, $book->b_user_id, '127.0.0.1');
        $payment = VnpayPayment::where('book_tour_id', $book->id)->firstOrFail();
        $data = array_merge([
            'vnp_TmnCode' => 'TESTCODE', 'vnp_TxnRef' => $payment->reference,
            'vnp_Amount' => '20000000', 'vnp_ResponseCode' => '00',
            'vnp_TransactionStatus' => '00', 'vnp_TransactionNo' => '123456',
            'vnp_BankCode' => 'NCB',
        ], $overrides);
        // Sign independently from production code.
        ksort($data);
        $data['vnp_SecureHash'] = hash_hmac('sha512', http_build_query($data, '', '&', PHP_QUERY_RFC1738), 'test-secret');
        return $data;
    }

    public function test_checkout_uses_database_amount_and_reuses_active_attempt(): void
    {
        $book = $this->booking();
        $this->actingAs($book->user, 'users');
        $this->get(route('vnpay.checkout', $book->id))->assertOk()->assertSee('200.000');
        $response = $this->post(route('vnpay.pay', $book->id), ['amount' => 1])->assertRedirect();
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $data);
        $this->assertSame('20000000', $data['vnp_Amount']);
        $hash = $data['vnp_SecureHash'];
        unset($data['vnp_SecureHash']);
        ksort($data);
        $this->assertSame(hash_hmac('sha512', http_build_query($data, '', '&', PHP_QUERY_RFC1738), 'test-secret'), $hash);
        $this->post(route('vnpay.pay', $book->id))->assertRedirect();
        $this->assertDatabaseCount('vnpay_payments', 1);
    }

    public function test_other_customer_cannot_view_or_pay_booking(): void
    {
        $book = $this->booking();
        $this->actingAs(User::factory()->create(), 'users');
        $this->get(route('vnpay.checkout', $book->id))->assertNotFound();
        $this->post(route('vnpay.pay', $book->id))->assertNotFound();
        $this->assertDatabaseCount('vnpay_payments', 0);
    }

    public function test_invalid_signature_and_wrong_amount_do_not_mark_paid(): void
    {
        $book = $this->booking();
        $data = $this->signedResult($book);
        $data['vnp_SecureHash'] = 'fake';
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '97']);
        $this->getJson(route('vnpay.ipn', $this->signedResult($book, ['vnp_Amount' => '100'])))->assertJson(['RspCode' => '04']);
        $this->assertSame(BookTour::STATUS_CONFIRMED, (int) $book->fresh()->b_status);
    }

    public function test_ipn_is_session_independent_and_idempotent(): void
    {
        $book = $this->booking();
        $data = $this->signedResult($book);
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '00']);
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '02']);
        $this->assertSame(BookTour::STATUS_PAID, (int) $book->fresh()->b_status);
        $this->assertDatabaseCount('booking_status_histories', 1);
        $this->assertDatabaseHas('vnpay_payments', ['status' => 'paid', 'transaction_no' => '123456']);
        $this->get(route('vnpay.return', $data))->assertOk()->assertSee('Thanh toán thành công');
    }

    public function test_return_updates_booking_before_ipn_and_repeated_callbacks_are_safe(): void
    {
        $book = $this->booking(BookTour::STATUS_PENDING);
        $data = $this->signedResult($book);
        $this->get(route('vnpay.return', $data))->assertOk()->assertSee('Thanh toán thành công');
        $this->assertSame(BookTour::STATUS_PAID, (int) $book->fresh()->b_status);
        $this->get(route('vnpay.return', $data))->assertOk()->assertSee('Thanh toán thành công');
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '02']);
        $this->assertDatabaseCount('booking_status_histories', 1);
        $this->actingAs($book->user, 'users')->get(route('my.tour'))
            ->assertOk()->assertSee('Đã thanh toán')->assertDontSee('Thanh toán online');
    }

    public function test_invalid_return_cannot_mark_booking_paid(): void
    {
        $book = $this->booking();
        $data = $this->signedResult($book);
        $data['vnp_SecureHash'] = 'fake';
        $this->get(route('vnpay.return', $data))->assertOk()->assertSee('không hợp lệ');
        $this->get(route('vnpay.return', $this->signedResult($book, ['vnp_Amount' => '100'])))
            ->assertOk()->assertSee('không hợp lệ');
        $this->assertSame(BookTour::STATUS_CONFIRMED, (int) $book->fresh()->b_status);
        $this->assertDatabaseCount('booking_status_histories', 0);
    }

    public function test_failed_payment_can_be_retried(): void
    {
        $book = $this->booking();
        $data = $this->signedResult($book, ['vnp_ResponseCode' => '24', 'vnp_TransactionStatus' => '02', 'vnp_TransactionNo' => '0']);
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '00']);
        $this->assertSame(BookTour::STATUS_CONFIRMED, (int) $book->fresh()->b_status);
        app(VnpayService::class)->create($book->id, $book->b_user_id, '127.0.0.1');
        $this->assertDatabaseCount('vnpay_payments', 2);
    }

    public function test_late_success_on_cancelled_booking_requires_review(): void
    {
        $book = $this->booking();
        $data = $this->signedResult($book);
        $book->update(['b_status' => BookTour::STATUS_CANCELLED]);
        $this->getJson(route('vnpay.ipn', $data))->assertJson(['RspCode' => '00']);
        $this->assertSame(BookTour::STATUS_CANCELLED, (int) $book->fresh()->b_status);
        $this->assertDatabaseHas('vnpay_payments', ['status' => 'review']);
    }

    public function test_paid_and_cancelled_bookings_cannot_start_payment(): void
    {
        foreach ([BookTour::STATUS_PAID, BookTour::STATUS_CANCELLED] as $status) {
            $book = $this->booking($status);
            $this->actingAs($book->user, 'users')->post(route('vnpay.pay', $book->id))->assertSessionHas('error');
        }
        $this->assertDatabaseCount('vnpay_payments', 0);
    }

    public function test_pending_booking_can_be_paid_online(): void
    {
        $book = $this->booking(BookTour::STATUS_PENDING);
        $this->actingAs($book->user, 'users');
        $this->get(route('my.tour'))->assertOk()->assertSee('Thanh toán online');
        $this->get(route('vnpay.checkout', $book->id))->assertOk();
        $this->getJson(route('vnpay.ipn', $this->signedResult($book)))->assertJson(['RspCode' => '00']);
        $this->assertSame(BookTour::STATUS_PAID, (int) $book->fresh()->b_status);
        $this->assertDatabaseHas('booking_status_histories', ['book_tour_id' => $book->id, 'old_status' => 1, 'new_status' => 3]);
    }

    public function test_online_booking_button_leads_to_checkout(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $book = $this->booking();
        $this->actingAs($book->user, 'users');
        $this->get(route('book.tour', [$book->b_tour_id, 'tour-vnpay']))->assertOk()->assertSee('Thanh toán online');
        $payload = $book->only(['b_name', 'b_email', 'b_phone', 'b_address', 'b_number_adults', 'b_number_children', 'b_number_child6', 'b_number_child2']);
        $payload['b_start_date'] = now()->addDays(5)->format('Y-m-d');
        $payload['payment_method'] = 'vnpay';
        $response = $this->post(route('post.book.tour', $book->b_tour_id), $payload);
        $newBook = BookTour::latest('id')->first();
        $this->assertNotSame($book->id, $newBook->id);
        $response->assertRedirect(route('vnpay.checkout', $newBook->id));
    }

    public function test_missing_configuration_does_not_create_payment(): void
    {
        $book = $this->booking();
        config(['vnpay.hash_secret' => '']);
        $this->actingAs($book->user, 'users')->post(route('vnpay.pay', $book->id))->assertSessionHas('error');
        $this->assertDatabaseCount('vnpay_payments', 0);
    }
}
