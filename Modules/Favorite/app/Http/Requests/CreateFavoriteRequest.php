<?php

namespace Modules\Favorite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFavoriteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id', 'integer', 'gt:0'], //get(more than) zero
        ];
    }


    public function messages()
    {
        return [
            'product_id.required' => 'فیلد شناسه محصول الزامی است.',
            'product_id.exists' => 'شناسه محصول انتخاب شده وجود ندارد.',
            'product_id.integer' => 'شناسه محصول باید یک عدد صحیح باشد.',
            'product_id.gt' => 'شناسه محصول باید بزرگتر از صفر باشد.', // پیام سفارشی برای gt:0
        ];
    }
}
