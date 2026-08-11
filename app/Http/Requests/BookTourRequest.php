<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookTourRequest extends FormRequest
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
            'b_name'  => 'required|string|max:191',
            'b_email' => 'required|email|max:191',
            'b_phone' => 'required|string|max:20',
            'b_address' => 'required|string|max:255',
            'b_start_date' => 'required|date|after:today',
            'b_note' => 'nullable|string|max:1000',
            'b_number_adults' => 'required|integer|min:0',
            'b_number_children' => 'required|integer|min:0',
            'b_number_child6' => 'nullable|integer|min:0',
            'b_number_child2' => 'nullable|integer|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $totalGuests = (int) $this->input('b_number_adults')
                + (int) $this->input('b_number_children')
                + (int) $this->input('b_number_child6')
                + (int) $this->input('b_number_child2');

            if ($totalGuests < 1) {
                $validator->errors()->add('b_number_adults', 'Vui lòng chọn ít nhất một khách');
            }
        });
    }

    public function messages()
    {
        return [
            'b_name.required' => 'Vui lòng nhập vào họ tên',
            'b_email.required' => 'Vui lòng nhập vào email đăng nhập',
            'b_email.unique' => 'Email đăng nhập không thể trùng lặp',
            'b_email.max' => 'Email vượt quá số ký tự cho phép',
            'b_phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'b_address.required' => 'Vui lòng nhập địa chỉ',
            'b_start_date.required' => 'Vui lòng chọn ngày khởi hành mong muốn',
            'b_start_date.date' => 'Ngày khởi hành mong muốn không hợp lệ',
            'b_start_date.after' => 'Ngày khởi hành mong muốn phải lớn hơn ngày hiện tại',
            'b_number_adults.required' => 'Vui lòng nhập số người lớn',
            'b_number_children.required' => 'Vui lòng nhập số trẻ em',

        ];
    }
}
