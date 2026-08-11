<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourGuideAssignment extends Model
{
    use HasFactory;

    protected $table = 'tour_guide_assignments';
    public $timestamps = true;

    protected $fillable = [
        'tga_tour_id',
        'tga_guide_id',
        'tga_role',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tga_tour_id', 'id');
    }

    public function guide()
    {
        return $this->belongsTo(TourGuide::class, 'tga_guide_id', 'id');
    }
}
