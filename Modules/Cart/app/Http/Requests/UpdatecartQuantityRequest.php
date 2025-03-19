<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatecartQuantityRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1'],

        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
