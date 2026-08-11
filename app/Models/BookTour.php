<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTour extends Model
{
    use HasFactory;
    protected $table = 'book_tours';
    public $timestamps = true;

    const STATUS = [
        1 => 'Chờ xác nhận',
        2 => 'Đã xác nhận',
        3 => 'Đã thanh toán',
        4 => 'Hoàn tất',
        5 => 'Đã hủy',
    ];
    const CLASS_STATUS = [
        1 => 'btn-secondary',
        2 => 'btn-info',
        3 => 'btn-success',
        4 => 'btn-warning',
        5 => 'btn-danger',
    ];
    const ALLOWED_TRANSITIONS = [
        1 => [2, 5],
        2 => [3, 5],
        3 => [4, 5],
        4 => [],
        5 => [],
    ];

    protected $fillable = ['b_tour_id', 'b_tour_schedule_id', 'b_user_id', 'b_name', 'b_email', 'b_phone', 'b_address', 'b_start_date', 'b_end_date', 'b_note', 'b_number_adults', 'b_number_children','b_price_adults','b_price_children','b_number_child6','b_number_child2','b_price_child6','b_price_child2','b_status'];

    protected $casts = [
        'b_start_date' => 'datetime',
        'b_end_date' => 'datetime',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'b_tour_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'b_user_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsTo(TourSchedule::class, 'b_tour_schedule_id', 'id');
    }

    public function getTotalGuestsAttribute()
    {
        return (int) $this->b_number_adults
            + (int) $this->b_number_children
            + (int) $this->b_number_child6
            + (int) $this->b_number_child2;
    }

    public function getTotalPriceAttribute()
    {
        return ((int) $this->b_number_adults * (int) $this->b_price_adults)
            + ((int) $this->b_number_children * (int) $this->b_price_children)
            + ((int) $this->b_number_child6 * (int) $this->b_price_child6)
            + ((int) $this->b_number_child2 * (int) $this->b_price_child2);
    }

    public function canTransitionTo(int $status): bool
    {
        return in_array($status, self::ALLOWED_TRANSITIONS[(int) $this->b_status] ?? [], true);
    }
}
