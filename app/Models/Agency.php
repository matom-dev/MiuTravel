<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['active' => 'boolean'];

    public function tours()
    {
        return $this->hasMany(Tour::class, 'agency_id');
    }
}
