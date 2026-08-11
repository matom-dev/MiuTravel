<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'receiver_guard',
        'receiver_id',
        'type',
        'title',
        'message',
        'url',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function scopeForAdmin($query)
    {
        return $query->where('receiver_guard', 'admins')->whereNull('receiver_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('receiver_guard', 'users')->where('receiver_id', $userId);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
