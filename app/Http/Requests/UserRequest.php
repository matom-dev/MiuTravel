<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validate = [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$this->id,
            'role' => 'required|integer|exists:roles,id',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:1,2',
            'agency_name' => [Rule::requiredIf(fn () => Role::whereKey($this->input('role'))->where('name', 'dai-ly-du-lich')->exists()), 'nullable', 'string', 'max:255'],
            'images' => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ];
        if (! $this->route('id')) {
            $validate['password'] = 'required | max:191 ';
        }

        return $validate;
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập vào tên tài khoản',
            'email.unique' => 'Tên tài khoản không thể trùng lặp',
            'email.max' => 'Tên tài khoản vượt quá số ký tự cho phép',
            'password.required' => 'Vui lòng nhập mật khẩu đăng nhập',
            'role.required' => 'Vui lòng chọn vai trò của người dùng',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
