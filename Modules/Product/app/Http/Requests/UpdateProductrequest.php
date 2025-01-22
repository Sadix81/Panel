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
            'name' => ['required' , 'string' , 'max:255' , Rule::unique('products')->ignore($this->product)],
            'description' => ['required' , 'string' , 'max:255'],
            'status' => ['required' , 'integer' , 'in:0,1'],
            // 'price' => ['required' , 'int' , 'min:0'],
            // 'Quantity' => ['required' , 'string'],
            // 'color' => ['required' , 'string' , 'min:1'],
            // 'image' => ['nullable' , 'image' , 'mimes:jpeg,png,jpg,gif' , 'max:2048'], // Validate image
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['nullable', 'exists:categories,id', 'integer'],
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
