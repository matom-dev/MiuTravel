<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admins')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'a_title' => 'required | max:191 | unique:articles,a_title,'.$this->id,
            'a_category_id' => 'required',
            'a_content' => ['nullable', 'required_if:a_active,1', 'string'],
            'images'  => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'album_images' => 'nullable|array',
            'album_images.*' => 'image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ];
    }

    public function messages()
    {
        return [
            'a_title.required' => 'Dữ liệu không thể để trống',
            'a_title.unique' => 'Dữ liệu đã bị trùng',
            'a_title.max' => 'Vượt quá số ký tự cho phép',
            'a_category_id.required' => 'Dữ liệu không thể để trống',
            'a_content.required_if' => 'Bài viết xuất bản phải có nội dung.',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.max' => 'Dung lượng ảnh không được vượt quá 5MB',
            'album_images.*.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.max' => 'Dung lượng ảnh album không được vượt quá 5MB',
        ];
    }
}
