<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TourGuide extends Model
{
    use HasFactory;

    protected $table = 'tour_guides';
    public $timestamps = true;

    protected $fillable = [
        'tg_name',
        'tg_role',
        'tg_gender',
        'tg_birth_date',
        'tg_hometown',
        'tg_phone',
        'tg_email',
        'tg_experience',
        'tg_languages',
        'tg_photo',
        'tg_status',
        'tg_user_id',
    ];

    const ROLES = [
        'guide' => 'Hướng dẫn viên',
        'leader' => 'Trưởng đoàn',
        'both' => 'HDV & Trưởng đoàn',
    ];

    const ASSIGNMENT_ROLES = [
        'guide' => 'Hướng dẫn viên',
        'leader' => 'Trưởng đoàn',
    ];

    const GENDERS = [
        'male' => 'Nam',
        'female' => 'Nữ',
        'other' => 'Khác',
    ];

    const STATUS = [
        1 => 'Đang hoạt động',
        2 => 'Tạm ẩn',
    ];

    protected $casts = [
        'tg_birth_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('tg_status', 1);
    }

    public function scopeCanBeLeader($query)
    {
        return $query->whereIn('tg_role', ['leader', 'both']);
    }

    public function scopeCanBeGuide($query)
    {
        return $query->whereIn('tg_role', ['guide', 'both']);
    }

    public function createOrUpdate($request, $id = '')
    {
        $params = $request->except(['images', '_token', 'submit']);

        if ($request->hasFile('images')) {
            $image = upload_image('images');
            if ($image['code'] == 1) {
                $params['tg_photo'] = $image['name'];
            }
        }

        $params['tg_user_id'] = Auth::guard('admins')->id();

        if ($id) {
            return $this->find($id)->update($params);
        }

        return $this->create($params);
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_guide_assignments', 'tga_guide_id', 'tga_tour_id')
            ->withPivot('tga_role')
            ->withTimestamps();
    }
}
