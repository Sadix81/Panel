<?php

namespace Modules\ForgotPassword\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'password' => ['required', 'min:8', 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/' , 'string'],
            'confirmpassword' => ['required', 'min:8', 'regex:/[0-9]/', 'regex:/[A-Z]/', 'regex:/[a-z]/' , 'string'],
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
