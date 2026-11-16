<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSchedule extends Model
{
    use HasFactory;

    protected $table = 'tour_schedules';
    public $timestamps = true;

    protected $fillable = [
        'ts_tour_id',
        'ts_start_date',
        'ts_end_date',
        'ts_number_guests',
        'ts_number_registered',
        'ts_follow',
        'ts_status',
    ];

    const STATUS = [
        1 => 'Đang nhận khách',
        0 => 'Tạm ẩn',
    ];

    public function scopeActive($query)
    {
        return $query->where('ts_status', 1);
    }

    public function scopeAvailableForBooking($query)
    {
        return $query->active()
            ->whereDate('ts_start_date', '>', today())
            ->whereDate('registration_deadline', '>=', today())
            ->where('ts_number_guests', '>', 0);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'ts_tour_id', 'id');
    }

    public function bookTours()
    {
        return $this->hasMany(BookTour::class, 'b_tour_schedule_id', 'id');
    }

    public function getRemainingSeatsAttribute()
    {
        $reserved = $this->bookTours()->where('b_status', '!=', BookTour::STATUS_CANCELLED)
            ->selectRaw('SUM(COALESCE(b_number_adults, 0) + COALESCE(b_number_children, 0) + COALESCE(b_number_child6, 0) + COALESCE(b_number_child2, 0)) AS seats')
            ->value('seats');

        return max(0, (int) $this->ts_number_guests - (int) $reserved);
    }

    public function guideAssignments()
    {
        return $this->hasMany(ScheduleGuideAssignment::class, 'tour_schedule_id');
    }

    public function getEffectiveGuidesAttribute()
    {
        if ($this->guideAssignments->isNotEmpty()) {
            return $this->guideAssignments->map(fn ($assignment) => [
                'guide' => $assignment->guide, 'role' => $assignment->role,
            ]);
        }
        return $this->tour?->staffGuides->map(fn ($guide) => [
            'guide' => $guide, 'role' => $guide->pivot->tga_role,
        ]) ?? collect();
    }

}
