<?php

namespace App\Console\Commands;

use App\Models\BookTour;
use App\Models\Tour;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileTourDepartures extends Command
{
    protected $signature = 'tours:reconcile-departures {--apply : Liên kết đơn cũ khi khớp chính xác một đợt}';
    protected $description = 'Kiểm tra tour thiếu lịch, đơn chưa gắn đợt; mặc định chỉ báo cáo';

    public function handle(): int
    {
        $rows = [];
        Tour::where('t_status', Tour::STATUS_BOOKABLE)->chunkById(100, function ($tours) use (&$rows) {
            foreach ($tours as $tour) {
                if (!$tour->schedules()->availableForBooking()->exists()) {
                    $rows[] = ['Tour', $tour->id, 'Cần bổ sung đợt hợp lệ trước khi nhận đơn mới'];
                }
            }
        });
        BookTour::whereNull('b_tour_schedule_id')->chunkById(100, function ($bookings) use (&$rows) {
            foreach ($bookings as $booking) {
                DB::transaction(function () use ($booking, &$rows) {
                    $tour = Tour::whereKey($booking->b_tour_id)->lockForUpdate()->first();
                    $book = BookTour::whereKey($booking->id)->lockForUpdate()->first();
                    if (!$tour || !$book || $book->b_tour_schedule_id) {
                        return;
                    }
                    $matches = $tour->schedules()->whereDate('ts_start_date', $book->b_start_date)
                        ->whereDate('ts_end_date', $book->b_end_date)->get();
                    $schedule = $matches->count() === 1 ? $matches->first() : null;
                    if ($schedule && $book->b_status != BookTour::STATUS_CANCELLED && $schedule->remaining_seats < $book->total_guests) {
                        $schedule = null;
                    }
                    if ($schedule && $this->option('apply')) {
                        $book->b_tour_schedule_id = $schedule->id;
                        $book->save();
                    }
                    $rows[] = ['Đơn', $book->id, $schedule
                        ? ($this->option('apply') ? 'Đã liên kết đợt #' : 'Có thể liên kết đợt #').$schedule->id
                        : 'Cần kiểm tra thủ công: lịch không khớp duy nhất hoặc không đủ sức chứa'];
                });
            }
        });
        $this->table(['Loại', 'ID', 'Kết quả'], $rows);
        $this->info($this->option('apply') ? 'Đã đối soát. Ngày, giá và thanh toán của đơn giữ nguyên.' : 'Chỉ báo cáo; chưa thay đổi dữ liệu.');
        return self::SUCCESS;
    }
}
