<?php

namespace Modules\Weight\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeightRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:20', 'unique:weights'],
            'weight_value' => ['required' , 'numeric'],
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
