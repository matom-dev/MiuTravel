<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TourRequest extends FormRequest
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

        $rules = [
            //
            't_title' => 'required | max:191 | unique:tours,t_title,'.$this->id,
            't_location_id' => ['required'],
            't_price_adults' => ['required'],
            't_price_children' => ['required'],
            't_journeys' => ['required'],
            't_duration_days' => ['required', 'integer', 'min:1', 'max:60'],
            't_duration_nights' => ['required', 'integer', 'min:0', 'max:59'],
            't_schedule' => ['nullable', 'max:191'],
            'images'  => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'album_images' => 'nullable|array',
            'album_images.*' => 'image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'activity_title' => 'nullable|array',
            'activity_title.*' => 'nullable|max:191',
            'activity_icon' => 'nullable|array',
            'activity_icon.*' => 'nullable|max:80',
            'activity_description' => 'nullable|array',
            'activity_description.*' => 'nullable|max:1000',
            'guide_name' => 'nullable|array',
            'guide_name.*' => 'nullable|max:191',
            'guide_role' => 'nullable|array',
            'guide_role.*' => 'nullable|max:191',
            'guide_phone' => 'nullable|array',
            'guide_phone.*' => 'nullable|max:30',
            'guide_email' => 'nullable|array',
            'guide_email.*' => 'nullable|email|max:191',
            'guide_experience' => 'nullable|array',
            'guide_experience.*' => 'nullable|max:191',
            'guide_languages' => 'nullable|array',
            'guide_languages.*' => 'nullable|max:191',
            'guide_photo' => 'nullable|array',
            'guide_photo.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|mimetypes:image/jpeg,image/png,image/webp|max:5120|dimensions:min_width=1,min_height=1,max_width=8000,max_height=8000',
            'guide_photo_old' => 'nullable|array',
            'guide_photo_old.*' => 'nullable|max:191',
            'tour_leader_id' => 'nullable|integer|exists:tour_guides,id',
            'tour_guide_ids' => 'nullable|array',
            'tour_guide_ids.*' => 'nullable|integer|exists:tour_guides,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            't_title.required'       => 'Dữ liệu không được phép để trống',
            't_title.max'            => 'Vượt quá số ký tự cho phép',
            't_title.unique'            => 'Dữ liệu đã bị trùng',
            't_location_id.required'      => 'Dữ liệu không được phép để trống',
            't_price_adults.required'      => 'Dữ liệu không được phép để trống',
            't_price_children.required'      => 'Dữ liệu không được phép để trống',
            't_journeys.required'      => 'Dữ liệu không được phép để trống',
            't_duration_days.required'      => 'Vui lòng nhập số ngày của tour',
            't_duration_days.integer'      => 'Số ngày không hợp lệ',
            't_duration_days.min'      => 'Tour phải có ít nhất 1 ngày',
            't_duration_nights.required'      => 'Vui lòng nhập số đêm của tour',
            't_duration_nights.integer'      => 'Số đêm không hợp lệ',
            't_duration_nights.min'      => 'Số đêm không được âm',
            'images.image'                  => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes'                  => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.image'          => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.mimes'          => 'Vui lòng nhập đúng định dạng file ảnh',
            'album_images.*.max'            => 'Dung lượng ảnh không được vượt quá 5MB',
            'activity_title.*.max'           => 'Tên hoạt động không được vượt quá 191 ký tự',
            'activity_icon.*.max'            => 'Mã icon không được vượt quá 80 ký tự',
            'activity_description.*.max'     => 'Mô tả hoạt động không được vượt quá 1000 ký tự',
            'guide_name.*.max'               => 'Tên hướng dẫn viên không được vượt quá 191 ký tự',
            'guide_role.*.max'               => 'Vai trò không được vượt quá 191 ký tự',
            'guide_phone.*.max'              => 'Số điện thoại không được vượt quá 30 ký tự',
            'guide_email.*.email'            => 'Email hướng dẫn viên không đúng định dạng',
            'guide_email.*.max'              => 'Email hướng dẫn viên không được vượt quá 191 ký tự',
            'guide_experience.*.max'         => 'Kinh nghiệm không được vượt quá 191 ký tự',
            'guide_languages.*.max'          => 'Ngôn ngữ không được vượt quá 191 ký tự',
            'guide_photo.*.image'             => 'Ảnh hướng dẫn viên phải là file ảnh',
            'guide_photo.*.mimes'             => 'Ảnh hướng dẫn viên chỉ hỗ trợ jpg, jpeg, png, webp',
            'guide_photo.*.max'               => 'Dung lượng ảnh hướng dẫn viên không được vượt quá 5MB',
            'tour_leader_id.integer'           => 'Trưởng đoàn không hợp lệ',
            'tour_leader_id.exists'            => 'Trưởng đoàn không tồn tại',
            'tour_guide_ids.*.integer'         => 'Hướng dẫn viên không hợp lệ',
            'tour_guide_ids.*.exists'          => 'Hướng dẫn viên không tồn tại',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ((int) $this->input('t_duration_nights') > (int) $this->input('t_duration_days')) {
                $validator->errors()->add('t_duration_nights', 'Số đêm không được lớn hơn số ngày');
            }
        });
    }
}
