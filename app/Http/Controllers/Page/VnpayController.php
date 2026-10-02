<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\BookTour;
use App\Models\VnpayPayment;
use App\Services\VnpayService;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function checkout(int $id, VnpayService $vnpay)
    {
        $book = BookTour::where('b_user_id', auth('users')->id())->findOrFail($id);
        if (!in_array((int) $book->b_status, [BookTour::STATUS_PENDING, BookTour::STATUS_CONFIRMED], true)) {
            return redirect()->route('my.tour')->with('error', 'Đơn đã thanh toán, hoàn tất hoặc đã hủy.');
        }

        return view('page.vnpay.checkout', ['book' => $book, 'configured' => $vnpay->configured()]);
    }

    public function pay(Request $request, int $id, VnpayService $vnpay)
    {
        try {
            return redirect()->away($vnpay->create($id, (int) auth('users')->id(), $request->ip()));
        } catch (\DomainException $exception) {
            return redirect()->route('vnpay.checkout', $id)->with('error', $exception->getMessage());
        }
    }

    public function ipn(Request $request, VnpayService $vnpay)
    {
        try {
            return response()->json($vnpay->receive($this->data($request)));
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['RspCode' => '99', 'Message' => 'Please retry']);
        }
    }

    public function result(Request $request, VnpayService $vnpay)
    {
        $data = $this->data($request);
        $message = 'Kết quả thanh toán không hợp lệ. Vui lòng kiểm tra đơn đã đặt hoặc liên hệ Miu Travel.';
        try {
            $result = $vnpay->receive($data);
        } catch (\Throwable $exception) {
            report($exception);
            $message = 'Chưa thể ghi nhận kết quả thanh toán. Vui lòng kiểm tra lại Tour đã đặt hoặc liên hệ Miu Travel, không thanh toán lại.';
            return view('page.vnpay.result', compact('message'));
        }
        if (in_array($result['RspCode'], ['00', '02'], true)) {
            $payment = VnpayPayment::where('reference', $data['vnp_TxnRef'] ?? '')->first();
            if ($payment && ($data['vnp_Amount'] ?? '') === (string) ($payment->amount * 100)) {
                $message = match ($payment->status) {
                    'paid' => 'Thanh toán thành công. Miu Travel đã ghi nhận thanh toán của bạn.',
                    'review' => 'Miu Travel đã nhận thông báo thanh toán và đang đối soát đơn. Vui lòng không thanh toán lại.',
                    default => ($data['vnp_ResponseCode'] ?? '') === '00' && ($data['vnp_TransactionStatus'] ?? '') === '00'
                        ? 'VNPay báo giao dịch thành công. Hệ thống đang chờ xác nhận thanh toán. Vui lòng kiểm tra lại trong Tour đã đặt và không thanh toán lại.'
                        : 'Giao dịch chưa thành công hoặc đã bị hủy. Bạn có thể quay lại Tour đã đặt để thử lại.',
                };
            }
        }

        // Return and IPN share signature validation, booking locks and duplicate protection.
        return view('page.vnpay.result', compact('message'));
    }

    private function data(Request $request): array
    {
        return array_filter($request->query(), fn ($key) => str_starts_with($key, 'vnp_'), ARRAY_FILTER_USE_KEY);
    }
}
