<?php

namespace Modules\Promotion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountallProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:0,1'],
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
