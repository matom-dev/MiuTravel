<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    public $timestamps = true;

    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_HIDDEN = 3;

    const STATUS = [
        self::STATUS_PENDING => 'Chờ duyệt',
        self::STATUS_APPROVED => 'Đã duyệt',
        self::STATUS_HIDDEN => 'Ẩn',
    ];

    const CLASS_STATUS = [
        self::STATUS_PENDING => 'btn-warning',
        self::STATUS_APPROVED => 'btn-success',
        self::STATUS_HIDDEN => 'btn-secondary',
    ];

    protected $fillable = [
        'cm_reply_id',
        'cm_user_id',
        'cm_article_id',
        'cm_hotel_id',
        'cm_tour_id',
        'cm_content',
        'cm_rating',
        'cm_images',
        'cm_status',
    ];

    protected $casts = [
        'cm_images' => 'array',
        'cm_rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'cm_user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'cm_reply_id', 'id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'cm_article_id', 'id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'cm_tour_id', 'id');
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'cm_hotel_id', 'id');
    }
}
