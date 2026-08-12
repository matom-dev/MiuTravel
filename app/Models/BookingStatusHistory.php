<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_tour_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_guard',
        'note',
    ];

    public function booking()
    {
        return $this->belongsTo(BookTour::class, 'book_tour_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
