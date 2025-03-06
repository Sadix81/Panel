<?php

namespace Modules\Faq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'question' => ['required' , 'string' , 'max:255'],
            'answer' => ['required' , 'string' , 'max:255'],
        ];
    }


    public function authorize(): bool
    {
        return true;
    }
}
