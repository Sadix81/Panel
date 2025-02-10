<?php

namespace Modules\Discount\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountAllProductsrequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:percentage,fixed'],
            'amount' => ['nullable', 'numeric', 'gt:0'],
        ];
    }

    
    public function messages()
    {
        return [
            'type.in' => 'نوع تخفیف باید درصدی یا ثابت باشد.',
            'amount.numeric' => 'مقدار تخفیف باید یک عدد باشد.',
            'amount.gt' => 'مقدار تخفیف باید بزرگ‌تر از صفر باشد.',
        ];
    }
}
