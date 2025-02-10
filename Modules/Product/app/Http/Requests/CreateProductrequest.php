<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductrequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'integer', 'in:0,1'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],

            'price' => ['required', 'numeric', 'gt:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'color_id' => ['nullable', 'array' , 'min:1'],
            'color_id.*' => ['nullable', 'exists:colors,id', 'integer', 'min:1'],
            'size_id' => ['nullable', 'array' , 'min:1'],
            'size_id.*' => ['nullable', 'exists:sizes,id', 'integer', 'min:1'],
            'category_id' => ['required', 'array' , 'min:1'],
            'category_id.*' => ['required', 'exists:categories,id', 'integer', 'gt:0'],
            'type' => ['nullable', 'in:percentage,fixed'],
            'amount' => ['nullable', 'numeric', 'gt:0'],
            // 'discounted_price' => ['nullable' , 'string'],
            'image_url' => ['nullable', 'array'],
            'image_url.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:10240'], // Max size 10MB
        ];
    }

   
    public function messages()
    {
        return [
            'name.required' => 'وارد کردن نام محصول الزامی است.',
            'name.string' => 'نام محصول باید یک رشته باشد.',
            'name.max' => 'نام محصول نمی‌تواند بیشتر از 255 کاراکتر باشد.',
            'description.max' => 'توضیحات محصول نمی‌تواند بیشتر از 255 کاراکتر باشد.',
            'status.required' => 'وارد کردن وضعیت محصول الزامی است.',
            'status.integer' => 'وضعیت محصول باید یک عدد صحیح باشد.',
            'status.in' => 'وضعیت محصول باید 0 یا 1 باشد.',
            'thumbnail.image' => 'تصویر باید از نوع jpeg، png یا jpg باشد.',
            'thumbnail.mimes' => 'تصویر باید از نوع jpeg، png یا jpg باشد.',
            'thumbnail.max' => 'حجم تصویر نباید از 2MB بیشتر باشد.',
            'price.required' => 'وارد کردن قیمت محصول الزامی است.',
            'price.numeric' => 'قیمت محصول باید یک عدد باشد.',
            'price.gt' => 'قیمت محصول باید بزرگ‌تر از صفر باشد.',
            'quantity.required' => 'وارد کردن مقدار موجودی الزامی است.',
            'quantity.integer' => 'مقدار موجودی باید یک عدد صحیح باشد.',
            'quantity.min' => 'مقدار موجودی باید بزرگ‌تر از صفر باشد.',
            'color_id.array' => 'رنگ‌ها باید به صورت آرایه ارسال شوند.',
            'color_id.min' => 'حداقل یک رنگ باید انتخاب شود.',
            'color_id.*.exists' => 'رنگ انتخاب شده معتبر نیست.',
            'size_id.array' => 'سایزها باید به صورت آرایه ارسال شوند.',
            'size_id.min' => 'حداقل یک سایز باید انتخاب شود.',
            'size_id.*.exists' => 'سایز انتخاب شده معتبر نیست.',
            'category_id.required' => 'وارد کردن دسته‌بندی الزامی است.',
            'category_id.array' => 'دسته‌بندی‌ها باید به صورت آرایه ارسال شوند.',
            'category_id.min' => 'حداقل یک دسته‌بندی باید انتخاب شود.',
            'category_id.*.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
            'type.in' => 'نوع تخفیف باید درصدی یا ثابت باشد.',
            'amount.numeric' => 'مقدار تخفیف باید یک عدد باشد.',
            'amount.gt' => 'مقدار تخفیف باید بزرگ‌تر از صفر باشد.',
            'image_url.array' => 'تصاویر باید به صورت آرایه ارسال شوند.',
            'image_url.*.image' => 'تصویر باید از نوع jpeg، png، jpg یا gif باشد.',
            'image_url.*.mimes' => 'تصویر باید از نوع jpeg، png، jpg یا gif باشد.',
            'image_url.*.max' => 'حجم تصویر نباید از 10MB بیشتر باشد.',
        ];
    }
}
