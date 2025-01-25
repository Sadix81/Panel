<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user)],
            'lastname' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user)],
            'mobile' => ['nullable', 'regex:/[0-9]{10}/', 'digits:11', Rule::unique('users')->ignore($this->user)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($this->user)],
            // 'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:5048'], // Max size 5MB
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
