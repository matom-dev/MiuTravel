<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        if (! $this->input('c_parent_id')) {
            $this->merge(['c_parent_id' => null]);
        }
    }

    public function rules()
    {
        return [
            //
            'c_name' => 'required | max:191 | unique:categories,c_name,'.$this->id,
            'c_parent_id' => ['nullable', 'integer', 'exists:categories,id', function ($attribute, $value, $fail) {
                $seen = [];
                $parent = (int) $value;
                while ($parent) {
                    if ($parent === (int) $this->route('id') || isset($seen[$parent])) {
                        $fail('Danh mục cha không được tạo thành vòng lặp.');
                        return;
                    }
                    $seen[$parent] = true;
                    $parent = (int) \App\Models\Category::whereKey($parent)->value('c_parent_id');
                }
            }],
            'c_description' => ['nullable', 'max:150'],
            'images'  => 'nullable|image|mimes:jpeg,jpg,png',
        ];
    }

    public function messages()
    {
        return [
            'c_name.required' => 'Dữ liệu không thể để trống',
            'c_name.unique' => 'Dữ liệu đã bị trùng',
            'c_name.max' => 'Vượt quá số ký tự cho phép',
            'c_description.max' => 'Vượt quá số ký tự cho phép',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
