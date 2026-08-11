<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\RichTextSanitizer;

class CarRental extends Model
{
    use HasFactory;

    protected $table = 'car_rentals';

    const STATUS = [
        1 => 'Xuất bản',
        2 => 'Bản nháp',
    ];

    protected $fillable = [
        'cr_name',
        'cr_image',
        'cr_album_images',
        'cr_location_id',
        'cr_user_id',
        'cr_vehicle_type',
        'cr_number_seats',
        'cr_transmission',
        'cr_fuel',
        'cr_phone',
        'cr_address',
        'cr_description',
        'cr_content',
        'cr_status',
    ];

    protected $casts = [
        'cr_album_images' => 'array',
    ];

    public function setCrDescriptionAttribute($value)
    {
        $this->attributes['cr_description'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function setCrContentAttribute($value)
    {
        $this->attributes['cr_content'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function getCrAlbumImagesAttribute($value)
    {
        return $this->normalizeAlbumImages($value);
    }

    public function getPhoneHrefAttribute()
    {
        $phone = trim((string) $this->cr_phone);
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

        return [];
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'cr_location_id', 'id')->where('l_status', 1);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cr_user_id', 'id');
    }

    public function createOrUpdate($request, $id = '')
    {
        $params = $request->except(['images', 'album_images', 'cr_price', 'cr_description', '_token', 'submit']);
        $sanitizedContent = app(RichTextSanitizer::class)->clean((string) $request->input('cr_content', ''));
        $plainContent = trim(preg_replace(
            '/\s+/u',
            ' ',
            html_entity_decode(strip_tags($sanitizedContent), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        ));

        $params['cr_content'] = $sanitizedContent;
        $params['cr_description'] = Str::limit($plainContent, 320, '');

        if ($request->hasFile('images')) {
            $image = upload_image('images');
            if ($image['code'] == 1) {
                $params['cr_image'] = $image['name'];
            }
        }

        if ($request->hasFile('album_images')) {
            $existingAlbum = [];
            if ($id) {
                $carRental = $this->find($id);
                $existingAlbum = $carRental ? $carRental->cr_album_images : [];
            }

            $uploadedAlbum = upload_multiple_images('album_images');
            $params['cr_album_images'] = array_merge($existingAlbum, $uploadedAlbum);
        }

        $params['cr_user_id'] = Auth::guard('admins')->id();

        if ($id) {
            return $this->find($id)->update($params);
        }

        return $this->create($params);
    }

    public function scopeActive($query)
    {
        return $query->where('cr_status', 1);
    }
}
