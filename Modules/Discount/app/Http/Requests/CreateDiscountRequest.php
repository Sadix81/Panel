<?php

namespace Modules\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiscountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'], // نام تخفیف
            'type' => ['required', 'in:percentage,fixed'], // نوع تخفیف (درصدی یا ثابت)
            'amount' => ['required', 'numeric', 'min:0'], // مقدار تخفیف (عدد مثبت)
            'minimum_purchase' => ['nullable', 'numeric', 'min:0'], // حداقل مبلغ خرید (اختیاری و عدد مثبت)
            'start_date' => ['nullable', 'date', 'after_or_equal:today'], // تاریخ شروع (باید تاریخ معتبری باشد و از امروز بعدی باشد)
            'end_date' => ['nullable', 'date', 'after:start_date'], // تاریخ پایان (باید بعد از تاریخ شروع باشد)
            'conditions' => ['nullable', 'string'], // شرایط استفاده (اختیاری)
            'usage_limit' => ['nullable', 'integer', 'min:1'], // محدودیت استفاده (اختیاری و عدد صحیح مثبت)
            'used_count' => ['nullable', 'integer', 'min:0'], // تعداد استفاده (اختیاری و عدد صحیح غیر منفی)
            'status' => ['nullable', 'in:0,1'],
            'allprductsdiscount' => ['nullable', 'in:0,1'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function messages()
    {
        return [
            'name.required' => 'نام تخفیف الزامی است.',
            'name.string' => 'نام تخفیف باید یک رشته باشد.',
            'name.max' => 'نام تخفیف نمی‌تواند بیشتر از 50 کاراکتر باشد.',
            'type.required' => 'نوع تخفیف الزامی است.',
            'type.in' => 'نوع تخفیف باید یکی از موارد "percentage" یا "fixed" باشد.',
            'amount.required' => 'مقدار تخفیف الزامی است.',
            'amount.numeric' => 'مقدار تخفیف باید یک عدد باشد.',
            'amount.min' => 'مقدار تخفیف باید یک عدد مثبت باشد.',
            'minimum_purchase.numeric' => 'حداقل مبلغ خرید باید یک عدد باشد.',
            'minimum_purchase.min' => 'حداقل مبلغ خرید باید یک عدد مثبت باشد.',
            'product_id.required' => 'شناسه محصول الزامی است.',
            'product_id.exists' => 'شناسه محصول باید در جدول محصولات وجود داشته باشد.',
            'start_date.required' => 'تاریخ شروع الزامی است.',
            'start_date.date' => 'تاریخ شروع باید یک تاریخ معتبر باشد.',
            'start_date.after_or_equal' => 'تاریخ شروع باید از امروز بعدی باشد.',
            'end_date.required' => 'تاریخ پایان الزامی است.',
            'end_date.date' => 'تاریخ پایان باید یک تاریخ معتبر باشد.',
            'end_date.after' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
            'conditions.string' => 'شرایط استفاده باید یک رشته باشد.',
            'usage_limit.integer' => 'محدودیت استفاده باید یک عدد صحیح باشد.',
            'usage_limit.min' => 'محدودیت استفاده باید حداقل 1 باشد.',
            'used_count.integer' => 'تعداد استفاده باید یک عدد صحیح باشد.',
            'used_count.min' => 'تعداد استفاده نمی‌تواند منفی باشد.',
        ];
    }
}
