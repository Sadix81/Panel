<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'currentpassword' => ['required', 'min:8', 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],
            'newpassword' => ['required', 'min:8', 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/'],
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
