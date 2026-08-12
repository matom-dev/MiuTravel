<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRentalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cr_name' => 'required|string|max:191',
            'cr_location_id' => 'nullable|integer',
            'cr_status' => 'required|in:1,2',
            'cr_vehicle_type' => 'nullable|string|max:100',
            'cr_number_seats' => 'nullable|integer|min:1|max:99',
            'cr_transmission' => 'nullable|string|max:50',
            'cr_fuel' => 'nullable|string|max:50',
            'cr_phone' => ['nullable', 'required_if:cr_status,1', 'string', 'max:30', 'regex:/^[0-9+().\s-]{8,30}$/'],
            'cr_address' => 'nullable|string|max:255',
            'cr_content' => ['nullable', 'required_if:cr_status,1', 'string'],
            'images' => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'album_images' => 'nullable|array',
            'album_images.*' => 'image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ];
    }

    public function messages()
    {
        return [
            'cr_name.required' => 'Vui lòng nhập tên dịch vụ thuê xe',
            'cr_name.max' => 'Tên dịch vụ thuê xe vượt quá số ký tự cho phép',
            'cr_status.in' => 'Trạng thái không hợp lệ',
            'cr_number_seats.integer' => 'Số chỗ phải là số',
            'cr_number_seats.min' => 'Số chỗ không hợp lệ',
            'cr_phone.required_if' => 'Dịch vụ thuê xe xuất bản phải có số điện thoại liên hệ.',
            'cr_phone.regex' => 'Số điện thoại đơn vị cho thuê không hợp lệ.',
            'cr_content.required_if' => 'Dịch vụ thuê xe xuất bản phải có nội dung.',
            'images.image' => 'Vui lòng chọn đúng định dạng file ảnh',
            'images.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp',
            'images.max' => 'Dung lượng ảnh không được vượt quá 5MB',
            'album_images.*.image' => 'Vui lòng chọn đúng định dạng file ảnh',
            'album_images.*.mimes' => 'Ảnh album phải có định dạng jpg, jpeg, png hoặc webp',
            'album_images.*.max' => 'Dung lượng ảnh album không được vượt quá 5MB',
        ];
    }
}
