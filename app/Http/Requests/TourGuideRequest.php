<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TourGuideRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tg_name' => 'required|max:191',
            'tg_role' => 'required|in:guide,leader,both',
            'tg_gender' => 'nullable|in:male,female,other',
            'tg_birth_date' => 'nullable|date|before:today',
            'tg_hometown' => 'nullable|max:191',
            'tg_phone' => 'nullable|max:30',
            'tg_email' => 'nullable|email|max:191',
            'tg_experience' => 'nullable|max:191',
            'tg_languages' => 'nullable|max:191',
            'tg_status' => 'required|in:1,2',
            'images' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'tg_name.required' => 'Vui lòng nhập họ tên',
            'tg_name.max' => 'Họ tên không được vượt quá 191 ký tự',
            'tg_role.required' => 'Vui lòng chọn vai trò',
            'tg_role.in' => 'Vai trò không hợp lệ',
            'tg_gender.in' => 'Giới tính không hợp lệ',
            'tg_birth_date.date' => 'Ngày sinh không hợp lệ',
            'tg_birth_date.before' => 'Ngày sinh phải nhỏ hơn ngày hiện tại',
            'tg_hometown.max' => 'Quê quán không được vượt quá 191 ký tự',
            'tg_phone.max' => 'Số điện thoại không được vượt quá 30 ký tự',
            'tg_email.email' => 'Email không đúng định dạng',
            'tg_email.max' => 'Email không được vượt quá 191 ký tự',
            'tg_experience.max' => 'Kinh nghiệm không được vượt quá 191 ký tự',
            'tg_languages.max' => 'Ngôn ngữ không được vượt quá 191 ký tự',
            'tg_status.required' => 'Vui lòng chọn trạng thái',
            'tg_status.in' => 'Trạng thái không hợp lệ',
            'images.image' => 'Ảnh nhân sự phải là file ảnh',
            'images.mimes' => 'Ảnh chỉ hỗ trợ jpg, jpeg, png, webp',
            'images.max' => 'Dung lượng ảnh không được vượt quá 5MB',
        ];
    }
}
