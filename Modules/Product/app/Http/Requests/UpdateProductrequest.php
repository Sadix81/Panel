<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductrequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required' , 'string' , 'max:255'],
            'description' => ['nullable' , 'string' , 'max:255'],
            'status' => ['required' , 'integer' , 'in:0,1'],

            'price' => ['required' , 'int' , 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'color_id' => ['nullable' , 'array'],
            'color_id.*' => ['nullable' , 'exists:colors,id' , 'integer' , 'min:1'],
            'size_id' => ['nullable' , 'array'],
            'size_id.*' => ['nullable' , 'exists:sizes,id' , 'integer' , 'min:1'],
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'exists:categories,id', 'integer', 'gt:0'],
            // 'image' => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'], // Validate image
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
