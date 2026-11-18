<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TourScheduleService
{
    public static function rules(): array
    {
        return [
            'ts_start_date' => 'required|date',
            'ts_end_date' => 'required|date|after_or_equal:ts_start_date',
            'registration_deadline' => 'nullable|date|before_or_equal:ts_start_date',
            'ts_number_guests' => 'required|integer|between:1,10000',
            'adult_price' => 'required|integer|between:0,2000000000',
            'child_price' => 'required|integer|between:0,2000000000',
            'ts_status' => 'required|in:0,1',
        ];
    }

    // Caller may extend this transaction to update guide assignments atomically.
    public function save(Tour $tour, array $data, ?int $scheduleId = null): TourSchedule
    {
        return DB::transaction(function () use ($tour, $data, $scheduleId) {
            Tour::whereKey($tour->id)->lockForUpdate()->firstOrFail();
            $schedule = $scheduleId
                ? $tour->schedules()->whereKey($scheduleId)->lockForUpdate()->firstOrFail()
                : new TourSchedule;
            $fail = fn ($message) => throw ValidationException::withMessages(['ts_start_date' => $message]);
            if ($tour->schedules()->whereDate('ts_start_date', $data['ts_start_date'])
                ->when($scheduleId, fn ($q) => $q->where('id', '!=', $scheduleId))->exists()) {
                $fail('Mỗi tour chỉ có một đợt khởi hành trong cùng ngày.');
            }
            $reserved = $schedule->exists ? $schedule->bookTours()->where('b_status', '!=', 5)->get()->sum('total_guests') : 0;
            if ($data['ts_number_guests'] < $reserved) {
                $fail('Sức chứa không được nhỏ hơn số chỗ đã đặt.');
            }
            if ($schedule->exists && $schedule->bookTours()->exists()
                && (substr($schedule->ts_start_date, 0, 10) !== $data['ts_start_date']
                    || substr($schedule->ts_end_date, 0, 10) !== $data['ts_end_date'])) {
                $fail('Đợt đã có đăng ký không được đổi ngày đi/về.');
            }
            if ((!$schedule->exists || substr($schedule->ts_start_date, 0, 10) !== $data['ts_start_date'])
                && \Carbon\Carbon::parse($data['ts_start_date'])->startOfDay()->lte(today())) {
                $fail('Ngày khởi hành mới phải sau ngày hiện tại.');
            }
            $data['registration_deadline'] = $data['registration_deadline'] ?? $data['ts_start_date'];
            $schedule->forceFill($data + ['ts_tour_id' => $tour->id])->save();

            return $schedule;
        });
    }
}
