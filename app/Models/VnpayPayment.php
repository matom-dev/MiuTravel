<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VnpayPayment extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['amount' => 'integer', 'expires_at' => 'datetime'];
}
