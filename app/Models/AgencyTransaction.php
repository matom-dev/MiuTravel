<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyTransaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['occurred_at' => 'datetime'];

    public function booking()
    {
        return $this->belongsTo(BookTour::class, 'book_tour_id');
    }


    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

}
