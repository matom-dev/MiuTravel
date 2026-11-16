<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleGuideAssignment extends Model
{
    protected $fillable = ['tour_schedule_id', 'tour_guide_id', 'role'];

    public function schedule()
    {
        return $this->belongsTo(TourSchedule::class, 'tour_schedule_id');
    }

    public function guide()
    {
        return $this->belongsTo(TourGuide::class, 'tour_guide_id');
    }
}
