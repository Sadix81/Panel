<?php

namespace Modules\Color\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:colors'],
            'code' => ['required', 'string', 'max:50', 'unique:colors,code'],
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
