<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\RichTextSanitizer;

class Article extends Model
{
    use HasFactory;
    protected $table = 'articles';
    public $timestamps = true;
    const ACTIVES = [
        1 => 'Xuất bản',
        2 => 'Bản nháp'
    ];
    protected $fillable = [
        'a_title',
        'a_slug',
        'a_hot',
        'a_active',
        'a_view',
        'a_description',
        'a_avatar',
        'a_album_images',
        'a_content',
        'a_category_id',
        'a_user_id',
    ];

    protected $casts = [
        'a_album_images' => 'array',
    ];

    public function setADescriptionAttribute($value)
    {
        $this->attributes['a_description'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function setAContentAttribute($value)
    {
        $this->attributes['a_content'] = app(RichTextSanitizer::class)->clean($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'a_category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'a_user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'cm_article_id', 'id');
    }

    /**
     * @param $request
     * @param string $id
     * @return mixed
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['images', 'album_images', 'a_description', '_token', 'submit']);
        $sanitizedContent = app(RichTextSanitizer::class)->clean((string) $request->input('a_content', ''));
        $plainContent = trim(preg_replace(
            '/\s+/u',
            ' ',
            html_entity_decode(strip_tags($sanitizedContent), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        ));

        $params['a_content'] = $sanitizedContent;
        $params['a_description'] = Str::limit($plainContent, 320, '');

        if ($request->hasFile('images')) {
            $image = upload_image('images');
            if ($image['code'] == 1)
                $params['a_avatar'] = $image['name'];
        }

        if ($request->hasFile('album_images')) {
            $existingAlbum = [];
            if ($id) {
                $article = $this->find($id);
                $existingAlbum = $article && $article->a_album_images ? $article->a_album_images : [];
            }

            $uploadedAlbum = upload_multiple_images('album_images');
            $params['a_album_images'] = array_merge($existingAlbum, $uploadedAlbum);
        }

        $params['a_slug'] = Str::slug($request->a_title);
        $params['a_user_id'] = Auth::guard('admins')->id();
        if ($id) {
            return $this->find($id)->update($params);
        }
        return $this->create($params);
    }

    public function scopeActive($query)
    {
        return $query->where('a_active', 1);
    }
}
