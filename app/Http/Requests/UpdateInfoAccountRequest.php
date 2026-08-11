<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = \Auth::guard('users')->id();

        return [
            //
            'name'  => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$userId,
            'phone' => 'required',
            'address' => 'required',
            'images' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập vào họ tên',
            'email.required' => 'Vui lòng nhập vào email đăng nhập',
            'email.unique' => 'Email đăng nhập không thể trùng lặp',
            'email.max' => 'Email vượt quá số ký tự cho phép',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'address.required' => 'Vui lòng nhập địa chỉ',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.max' => 'Dung lượng ảnh không được vượt quá 5MB',

        ];
    }
}
