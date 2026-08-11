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
        return max(0, (int) $this->ts_number_guests - (int) $this->ts_number_registered - (int) $this->ts_follow);
    }
}
