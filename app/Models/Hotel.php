<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\RichTextSanitizer;

class Hotel extends Model
{
    use HasFactory;
    protected $table = 'hotels';
    public $timestamps = true;

    const STATUS = [
        1 => 'Xuất bản',
        2 => 'Bản nháp'
    ];

    const ACCOMMODATION_TYPES = [
        'hotel' => 'Khách sạn',
        'resort' => 'Resort',
        'homestay' => 'Homestay',
        'guest_house' => 'Nhà khách',
        'hostel' => 'Nhà nghỉ',
        'villa' => 'Biệt thự',
        'apartment' => 'Căn hộ',
    ];

    const AMENITIES = [
        'near_beach' => 'Gần biển',
        'breakfast' => 'Có phục vụ bữa sáng',
        'wifi' => 'Wi-Fi',
        'parking' => 'Bãi đỗ xe',
        'restaurant' => 'Nhà hàng',
        'pool' => 'Hồ bơi',
        'air_conditioning' => 'Điều hòa',
    ];

    const SUITABLE_FOR = [
        'family' => 'Gia đình',
        'couple' => 'Cặp đôi',
        'business' => 'Khách công tác',
        'group' => 'Nhóm bạn',
        'pets' => 'Cho phép thú cưng',
    ];

    protected $fillable = [
        'h_name',
        'h_image',
        'h_anbum_image',
        'h_address',
        'h_phone',
        'h_accommodation_type',
        'h_star_rating',
        'h_amenities',
        'h_suitable_for',
        'h_description',
        'h_content',
        'h_status',
        'h_location_id',
        'h_user_id',
    ];

    protected $casts = [
        'h_anbum_image' => 'array',
        'h_star_rating' => 'integer',
        'h_amenities' => 'array',
        'h_suitable_for' => 'array',
    ];

    public function setHDescriptionAttribute($value)
    {
        $this->attributes['h_description'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function setHContentAttribute($value)
    {
        $this->attributes['h_content'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function getHAnbumImageAttribute($value)
    {
        return $this->normalizeAlbumImages($value);
    }

    public function getPhoneHrefAttribute()
    {
        $phone = trim((string) $this->h_phone);
        $prefix = substr($phone, 0, 1) === '+' ? '+' : '';

        return $prefix.preg_replace('/\D+/', '', $phone);
    }

    protected function normalizeAlbumImages($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        if (!is_string($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        $decodedAgain = json_decode($decoded ?? '', true);
        if (is_array($decodedAgain)) {
            return array_values(array_filter($decodedAgain));
        }

        return [];
    }

    public function location ()
    {
        return $this->belongsTo(Location::class, 'h_location_id', 'id')->where('l_status', 1);
    }

    public function user ()
    {
        return $this->belongsTo(User::class, 'h_user_id', 'id');
    }

    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['images', 'album_images', 'h_description', '_token', 'submit']);
        $sanitizedContent = app(RichTextSanitizer::class)->clean((string) $request->input('h_content', ''));
        $plainContent = trim(preg_replace(
            '/\s+/u',
            ' ',
            html_entity_decode(strip_tags($sanitizedContent), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        ));

        $params['h_content'] = $sanitizedContent;
        $params['h_description'] = Str::limit($plainContent, 320, '');
        $params['h_amenities'] = array_values($request->input('h_amenities', []));
        $params['h_suitable_for'] = array_values($request->input('h_suitable_for', []));

        // Upload ảnh đại diện
        if ($request->hasFile('images')) {
            $image = upload_image('images');
            if ($image['code'] == 1)
                $params['h_image'] = $image['name'];
        }

        // Upload nhiều ảnh album
        if ($request->hasFile('album_images')) {
            $existingAlbum = [];
            if ($id) {
                $hotel = $this->find($id);
                $existingAlbum = $hotel->h_anbum_image ? $hotel->h_anbum_image : [];
            }
            $uploadedAlbum = upload_multiple_images('album_images');
            $params['h_anbum_image'] = array_merge($existingAlbum, $uploadedAlbum);
        }

        $params['h_user_id'] = Auth::guard('admins')->id();
        if ($id) {
            return $this->find($id)->update($params);
        }
        return $this->create($params);
    }

    public function scopeActive($query)
    {
        return $query->where('h_status', 1);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'cm_hotel_id', 'id');
    }
}
