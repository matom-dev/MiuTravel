<?php

namespace App\Services;

use App\Models\BookTour;
use App\Models\VnpayPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VnpayService
{
    public function configured(): bool
    {
        return filled(config('vnpay.tmn_code')) && filled(config('vnpay.hash_secret'));
    }

    public function sign(array $data): string
    {
        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);
        ksort($data);

        return hash_hmac('sha512', http_build_query($data, '', '&', PHP_QUERY_RFC1738), config('vnpay.hash_secret'));
    }

    public function valid(array $data): bool
    {
        foreach ($data as $value) {
            if (!is_string($value)) {
                return false;
            }
        }

        return $this->configured()
            && ($data['vnp_TmnCode'] ?? '') === config('vnpay.tmn_code')
            && isset($data['vnp_SecureHash'])
            && hash_equals($this->sign($data), $data['vnp_SecureHash']);
    }

    public function create(int $bookingId, int $userId, string $ip): string
    {
        if (!$this->configured()) {
            throw new \DomainException('VNPay chưa được cấu hình. Vui lòng liên hệ Miu Travel.');
        }

        return DB::transaction(function () use ($bookingId, $userId, $ip) {
            $book = BookTour::where('b_user_id', $userId)->lockForUpdate()->findOrFail($bookingId);
            if (!in_array((int) $book->b_status, [BookTour::STATUS_PENDING, BookTour::STATUS_CONFIRMED], true) || $book->total_price <= 0) {
                throw new \DomainException('Chỉ có thể thanh toán đơn đang chờ xác nhận hoặc đã xác nhận, chưa thanh toán.');
            }
            // Reuse an active attempt to avoid charging the same booking in multiple tabs.
            $payment = VnpayPayment::where('book_tour_id', $book->id)
                ->where('status', 'pending')->where('expires_at', '>', now())->first();
            if ($payment && $payment->amount !== (int) $book->total_price) {
                throw new \DomainException('Giá booking đã thay đổi. Vui lòng chờ yêu cầu thanh toán cũ hết hạn.');
            }
            $payment ??= VnpayPayment::create([
                'book_tour_id' => $book->id,
                'reference' => (string) Str::ulid(),
                'amount' => (int) $book->total_price,
                'expires_at' => now()->addMinutes(15),
            ]);
            $data = [
                'vnp_Version' => '2.1.0', 'vnp_Command' => 'pay',
                'vnp_TmnCode' => config('vnpay.tmn_code'),
                'vnp_Amount' => $payment->amount * 100, 'vnp_CurrCode' => 'VND',
                'vnp_TxnRef' => $payment->reference,
                'vnp_OrderInfo' => 'Thanh toan tour '.$book->display_code,
                'vnp_OrderType' => 'other', 'vnp_Locale' => 'vn',
                'vnp_IpAddr' => $ip, 'vnp_ReturnUrl' => route('vnpay.return'),
                'vnp_CreateDate' => $payment->created_at->copy()->timezone('Asia/Ho_Chi_Minh')->format('YmdHis'),
                'vnp_ExpireDate' => $payment->expires_at->copy()->timezone('Asia/Ho_Chi_Minh')->format('YmdHis'),
            ];
            $data['vnp_SecureHash'] = $this->sign($data);

            return config('vnpay.url').'?'.http_build_query($data, '', '&', PHP_QUERY_RFC1738);
        });
    }

    public function receive(array $data): array
    {
        if (!$this->valid($data)) {
            return ['RspCode' => '97', 'Message' => 'Invalid signature'];
        }
        return DB::transaction(function () use ($data) {
            $attempt = VnpayPayment::where('reference', $data['vnp_TxnRef'] ?? '')->first();
            if (!$attempt) {
                return ['RspCode' => '01', 'Message' => 'Order not found'];
            }
            // Use the same lock order as checkout: booking first, payment second.
            $book = BookTour::lockForUpdate()->find($attempt->book_tour_id);
            $payment = VnpayPayment::lockForUpdate()->find($attempt->id);
            if (!$book) {
                return ['RspCode' => '01', 'Message' => 'Order not found'];
            }
            if (($data['vnp_Amount'] ?? '') !== (string) ($payment->amount * 100)) {
                return ['RspCode' => '04', 'Message' => 'Invalid amount'];
            }
            if ($payment->status !== 'pending') {
                return ['RspCode' => '02', 'Message' => 'Order already confirmed'];
            }
            if (!preg_match('/^\d{2}$/', $data['vnp_ResponseCode'] ?? '')
                || !preg_match('/^\d{2}$/', $data['vnp_TransactionStatus'] ?? '')) {
                return ['RspCode' => '99', 'Message' => 'Invalid result'];
            }
            $success = $data['vnp_ResponseCode'] === '00' && $data['vnp_TransactionStatus'] === '00';
            if ($success && !preg_match('/^[1-9]\d{0,14}$/', $data['vnp_TransactionNo'] ?? '')) {
                return ['RspCode' => '99', 'Message' => 'Invalid transaction'];
            }
            $payment->status = $success ? 'paid' : 'failed';
            $payment->response_code = $data['vnp_ResponseCode'];
            $payment->bank_code = $data['vnp_BankCode'] ?? null;
            $payment->transaction_no = $success ? $data['vnp_TransactionNo'] : null;
            if ($success) {
                if (in_array((int) $book->b_status, [BookTour::STATUS_PENDING, BookTour::STATUS_CONFIRMED], true) && (int) $book->total_price === $payment->amount) {
                    app(BookingService::class)->changeStatus($book, BookTour::STATUS_PAID, 'VNPay '.$payment->reference, null, 'vnpay');
                } else {
                    // Money received for a changed/cancelled/already-paid booking needs reconciliation.
                    $payment->status = 'review';
                    \App\Jobs\CreateAppNotification::dispatch([
                        'receiver_guard' => 'admins', 'type' => 'payment_review',
                        'title' => 'Cần đối soát thanh toán VNPay',
                        'message' => 'Giao dịch '.$payment->reference.' của đơn '.$book->display_code.' cần kiểm tra hoàn tiền hoặc trạng thái.',
                        'url' => route('book.tour.index', [], false),
                        'data' => ['book_tour_id' => $book->id, 'reference' => $payment->reference],
                    ])->afterCommit();
                }
            }
            $payment->save();

            return ['RspCode' => '00', 'Message' => 'Confirm Success'];
        });
    }
}
