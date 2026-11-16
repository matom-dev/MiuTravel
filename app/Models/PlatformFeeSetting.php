<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformFeeSetting extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['basis_points'=>'integer'];

    public static function currentBasisPoints(): int
    {
        return (int) (static::whereKey(1)->value('basis_points') ?? 1500);
    }
}
