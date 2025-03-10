<?php

namespace Modules\ForgotPassword\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordVerificationCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'digits:5'],
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
