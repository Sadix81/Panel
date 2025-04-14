<?php

namespace Modules\Slider\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'slider_image_url' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:10240'], // Max size 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'slider_image_url.required' => 'لطفاً حداقل یک تصویر بارگذاری کنید.',
            'slider_image_url.image' => 'فایل بارگذاری شده باید یک تصویر باشد.',
            'slider_image_url.mimes' => 'تصویر باید از نوع: jpeg، png یا jpg باشد.',
            'slider_image_url.max' => 'تصویر نباید بزرگ‌تر از ۱۰ مگابایت باشد.',
        ];
    }
}
