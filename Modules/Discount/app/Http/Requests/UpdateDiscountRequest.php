<?php

namespace Modules\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'start_date' => ['nullable', 'date', 'after_or_equal:today'], // تاریخ شروع (باید تاریخ معتبری باشد و از امروز بعدی باشد)
            'end_date' => ['nullable', 'date', 'after:start_date'], // تاریخ پایان (باید بعد از تاریخ شروع باشد)
            'minimum_purchase' => ['nullable', 'numeric', 'min:0'], // حداقل مبلغ خرید (اختیاری و عدد مثبت)
            'conditions' => ['nullable', 'string'], // شرایط استفاده (اختیاری)
            'usage_limit' => ['nullable', 'integer', 'min:1'], // محدودیت استفاده (اختیاری و عدد صحیح مثبت)
            'used_count' => ['nullable', 'integer', 'min:0'], // تعداد استفاده (اختیاری و عدد صحیح غیر منفی)
            'status' => ['nullable' , 'min:0' , 'max:1'],
            'allprductsdiscount' => ['nullable' , 'in:0,1']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
