<?php

namespace Modules\Rating\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'product_id' => ['required', 'string', 'exists:products,id', 'gt:0'],
            // 'user_id' => ['required' , 'string' , 'exists:products,id' , 'gt:0'],
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
