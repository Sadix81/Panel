<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user()->id)],
            'lastname' => ['nullable', 'max:100', 'string', Rule::unique('users')->ignore($this->user()->id)],
            'mobile' => ['nullable', 'regex:/[0-9]{10}/', 'digits:11', Rule::unique('users')->ignore($this->user()->id)],
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($this->user()->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,bmp,gif,svg,webp', 'max:10240'], // Max size 10MB
            'twofactor' => ['nullable', 'integer', 'in:0,1'],
            'country' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string', 'max:1000'],
            'codepost' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
