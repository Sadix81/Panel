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
            'name' => ['required' , 'string' , 'max:255' , 'unique:categories'],
            'description' => ['required' , 'string' , 'max:255'],
            'status' => ['required' , 'integer' , 'in:0,1'],
            // 'price' => ['required' , 'int' , 'min:0'],
            // 'Quantity' => ['required' , 'string'],
            // 'color' => ['required' , 'string' , 'min:1'],
            // 'image' => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'], // Validate image
            'category_id' => ['required', 'array'],
            'category_id.*' => ['required', 'exists:categories,id', 'integer', 'gt:0'],
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
