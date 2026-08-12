<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTour extends Model
{
    use HasFactory;
    protected $table = 'book_tours';
    public $timestamps = true;

    public const STATUS_PENDING = 1;
    public const STATUS_CONFIRMED = 2;
    public const STATUS_PAID = 3;
    public const STATUS_COMPLETED = 4;
    public const STATUS_CANCELLED = 5;

    const STATUS = [
        self::STATUS_PENDING => 'Chờ xác nhận',
        self::STATUS_CONFIRMED => 'Đã xác nhận',
        self::STATUS_PAID => 'Đã thanh toán',
        self::STATUS_COMPLETED => 'Hoàn tất',
        self::STATUS_CANCELLED => 'Đã hủy',
    ];
    const CLASS_STATUS = [
        self::STATUS_PENDING => 'btn-secondary',
        self::STATUS_CONFIRMED => 'btn-info',
        self::STATUS_PAID => 'btn-success',
        self::STATUS_COMPLETED => 'btn-warning',
        self::STATUS_CANCELLED => 'btn-danger',
    ];
    const ALLOWED_TRANSITIONS = [
        self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
        self::STATUS_CONFIRMED => [self::STATUS_PAID, self::STATUS_CANCELLED],
        self::STATUS_PAID => [self::STATUS_COMPLETED, self::STATUS_CANCELLED],
        self::STATUS_COMPLETED => [],
        self::STATUS_CANCELLED => [],
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

    public function statusHistories()
    {
        return $this->hasMany(BookingStatusHistory::class, 'book_tour_id');
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
