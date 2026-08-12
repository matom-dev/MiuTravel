<?php

namespace App\Http\Requests;

use App\Models\Hotel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
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
        return [
            'h_name' => 'required|string|max:191',
            'h_status' => 'required|in:1,2',
            'h_phone' => ['nullable', 'required_if:h_status,1', 'string', 'max:20', 'regex:/^[0-9+().\s-]{8,20}$/'],
            'h_accommodation_type' => ['required', Rule::in(array_keys(Hotel::ACCOMMODATION_TYPES))],
            'h_star_rating' => 'nullable|integer|between:1,5',
            'h_amenities' => 'nullable|array',
            'h_amenities.*' => ['string', Rule::in(array_keys(Hotel::AMENITIES))],
            'h_room_facilities' => 'nullable|array',
            'h_room_facilities.*' => ['string', Rule::in(array_keys(Hotel::ROOM_FACILITIES))],
            'h_property_policies' => 'nullable|array',
            'h_property_policies.*' => ['string', Rule::in(array_keys(Hotel::PROPERTY_POLICIES))],
            'h_meal_plans' => 'nullable|array',
            'h_meal_plans.*' => ['string', Rule::in(array_keys(Hotel::MEAL_PLANS))],
            'h_suitable_for' => 'nullable|array',
            'h_suitable_for.*' => ['string', Rule::in(array_keys(Hotel::SUITABLE_FOR))],
            'h_address' => 'required|string|max:255',
            'h_content' => ['nullable', 'required_if:h_status,1', 'string'],
            'images'  => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'album_images' => 'nullable|array',
            'album_images.*' => 'image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
        ];
    }

    public function messages()
    {
        return [
            'h_name.required' => 'Vui lòng nhập tên khách sạn',
            'h_name.max' => 'Tên khách sạn vượt quá số ký tự cho phép',
            'h_address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
            'h_status.in' => 'Trạng thái không hợp lệ',
            'h_phone.required_if' => 'Khách sạn xuất bản phải có số điện thoại lễ tân.',
            'h_phone.regex' => 'Số điện thoại khách sạn không hợp lệ.',
            'h_accommodation_type.required' => 'Vui lòng chọn loại hình lưu trú.',
            'h_accommodation_type.in' => 'Loại hình lưu trú không hợp lệ.',
            'h_star_rating.between' => 'Hạng sao phải từ 1 đến 5 sao.',
            'h_content.required_if' => 'Khách sạn xuất bản phải có nội dung.',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.max' => 'Dung lượng ảnh không được vượt quá 5MB',
            'album_images.*.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.max' => 'Dung lượng ảnh không được vượt quá 5MB',
        ];
    }
}
