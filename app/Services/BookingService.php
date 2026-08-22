<?php

namespace App\Services;

use App\Jobs\CreateAppNotification;
use App\Models\BookingStatusHistory;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function createForTour(int $tourId, User $user, array $data): array
    {
        return DB::transaction(function () use ($tourId, $user, $data) {
            $tour = Tour::where('id', $tourId)->lockForUpdate()->first();

            if (!$tour || (int) $tour->t_status !== 1) {
                throw new \DomainException('Tour không tồn tại hoặc không còn nhận đặt chỗ');
            }

            $adultPrice = $this->discountedPrice($tour->t_price_adults, $tour->t_sale);
            $childPrice = $this->discountedPrice($tour->t_price_children, $tour->t_sale);
            $startDate = Carbon::parse($data['b_start_date'])->startOfDay();
            $endDate = $this->expectedEndDate($startDate, $tour);

            $book = BookTour::create(array_merge($data, [
                'b_tour_id' => $tour->id,
                'b_tour_schedule_id' => null,
                'b_user_id' => $user->id,
                'b_status' => BookTour::STATUS_PENDING,
                'b_start_date' => $startDate->format('Y-m-d H:i:s'),
                'b_end_date' => $endDate->format('Y-m-d H:i:s'),
                'b_price_adults' => $adultPrice,
                'b_price_children' => $childPrice,
                'b_price_child6' => $childPrice * 50 / 100,
                'b_price_child2' => $childPrice * 25 / 100,
            ]));

            $book->b_code = BookTour::makeCode((int) $book->id, $book->created_at);
            $book->save();

            $this->recordStatusHistory($book, null, BookTour::STATUS_PENDING, $user, 'users', 'Khách tạo booking');

            CreateAppNotification::dispatch([
                'receiver_guard' => 'admins',
                'type' => 'booking_created',
                'title' => 'Có đơn đặt tour mới',
                'message' => 'Khách ' . $user->name . ' vừa đặt tour theo ngày khởi hành mong muốn '
                    . $book->b_start_date->format('d/m/Y') . ' - ' . $book->b_end_date->format('d/m/Y')
                    . ': "' . $tour->t_title . '".',
                'url' => route('book.tour.index', [], false),
                'data' => [
                    'book_tour_id' => $book->id,
                    'tour_id' => $tour->id,
                    'user_id' => $user->id,
                    'start_date' => $book->b_start_date->format('Y-m-d'),
                    'end_date' => $book->b_end_date->format('Y-m-d'),
                    'total_guests' => $book->total_guests,
                    'total_price' => $book->total_price,
                ],
            ]);

            return compact('book', 'tour', 'user');
        });
    }

    public function changeStatus(BookTour $booking, int $newStatus, ?string $note = null, ?User $actor = null, ?string $guard = null): array
    {
        return DB::transaction(function () use ($booking, $newStatus, $note, $actor, $guard) {
            $bookTour = BookTour::where('id', $booking->id)->lockForUpdate()->first();

            if (!$bookTour) {
                throw new \DomainException('Dữ liệu không tồn tại');
            }

            $currentStatus = (int) $bookTour->b_status;

            if ($newStatus === $currentStatus) {
                throw new \DomainException('Đơn đang ở trạng thái này rồi');
            }

            if (!in_array($newStatus, BookTour::ALLOWED_TRANSITIONS[$currentStatus] ?? [], true)) {
                throw new \DomainException('Thao tác chuyển trạng thái không hợp lệ');
            }

            $note = trim((string) $note);
            $bookTour->b_status = $newStatus;
            if ($newStatus === BookTour::STATUS_CANCELLED && $note !== '') {
                $bookTour->b_cancel_reason = $note;
            }
            $bookTour->save();

            $tour = Tour::where('id', $bookTour->b_tour_id)->first();
            if (!$tour) {
                throw new \DomainException('Tour của đơn đặt không tồn tại');
            }

            $this->recordStatusHistory(
                $bookTour,
                $currentStatus,
                $newStatus,
                $actor ?: auth('admins')->user(),
                $guard ?: (auth('admins')->check() ? 'admins' : null),
                $note !== '' ? $note : 'Cập nhật trạng thái booking'
            );

            CreateAppNotification::dispatch([
                'receiver_guard' => 'users',
                'receiver_id' => $bookTour->b_user_id,
                'type' => 'booking_status_updated',
                'title' => 'Đơn đặt tour đã cập nhật',
                'message' => 'Đơn "' . ($tour->t_title ?? 'tour') . '" theo ngày khởi hành mong muốn '
                    . ($bookTour->b_start_date ? $bookTour->b_start_date->format('d/m/Y') : '---')
                    . ' - ngày về dự kiến '
                    . ($bookTour->b_end_date ? $bookTour->b_end_date->format('d/m/Y') : '---')
                    . ' đã chuyển sang trạng thái ' . (BookTour::STATUS[$newStatus] ?? 'mới')
                    . '. Tổng tiền tạm tính: ' . number_format($bookTour->total_price, 0, ',', '.') . ' đ.',
                'url' => route('my.tour', [], false),
                'data' => [
                    'book_tour_id' => $bookTour->id,
                    'tour_id' => $tour->id,
                    'old_status' => $currentStatus,
                    'new_status' => $newStatus,
                    'start_date' => $bookTour->b_start_date ? $bookTour->b_start_date->format('Y-m-d') : null,
                    'end_date' => $bookTour->b_end_date ? $bookTour->b_end_date->format('Y-m-d') : null,
                    'total_guests' => $bookTour->total_guests,
                    'total_price' => $bookTour->total_price,
                ],
            ]);

            return [
                'bookTour' => $bookTour,
                'tour' => $tour,
                'user' => User::find($bookTour->b_user_id),
                'mail' => $this->mailForStatus($newStatus),
            ];
        });
    }

    private function discountedPrice($price, $sale)
    {
        return (int) $price - ((int) $price * (int) $sale / 100);
    }

    private function recordStatusHistory(
        BookTour $booking,
        ?int $oldStatus,
        int $newStatus,
        ?User $actor = null,
        ?string $guard = null,
        ?string $note = null
    ): void {
        BookingStatusHistory::create([
            'book_tour_id' => $booking->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $actor ? $actor->id : null,
            'changed_guard' => $guard,
            'note' => $note,
        ]);
    }

    private function expectedEndDate(Carbon $startDate, Tour $tour): Carbon
    {
        $durationDays = $tour->effective_duration_days;

        return $startDate->copy()->addDays($durationDays - 1)->endOfDay();
    }

    private function mailForStatus(int $status): ?array
    {
        return [
            BookTour::STATUS_CONFIRMED => ['view' => 'email', 'subject' => 'Xác nhận booking'],
            BookTour::STATUS_PAID => ['view' => 'emailtt', 'subject' => 'Xác nhận thanh toán'],
            BookTour::STATUS_CANCELLED => ['view' => 'emailhuy', 'subject' => 'Xác nhận HUỶ BOOKING'],
        ][$status] ?? null;
    }
}
