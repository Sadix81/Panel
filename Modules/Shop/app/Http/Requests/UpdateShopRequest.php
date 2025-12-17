<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'telephone' => ['nullable', 'integer'],
            'email' => ['required', 'email'],
            'country' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string', 'max:1000'],
            'codepost' => ['nullable', 'string'],
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
