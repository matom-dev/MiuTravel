<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    public function booking()
    {
        return $this->belongsTo(BookTour::class, 'book_tour_id');
    }

}
