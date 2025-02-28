<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required',  'exists:products,id', 'integer', 'min:1'],
            'color_id' => ['nullable',  'exists:colors,id', 'integer', 'min:1'],
            'size_id' => ['nullable',  'exists:sizes,id', 'integer', 'min:1'],

            // 'total_price' => ['nullable', 'numeric', 'gte:0'],
            // 'discounted_price' => ['nullable', 'numeric', 'gte:0'],
            // 'uuid' => ['nullable', 'string', 'size:36'],


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
