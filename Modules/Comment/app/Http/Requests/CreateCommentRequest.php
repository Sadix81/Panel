<?php

namespace Modules\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class CreateCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'text' => ['required' , 'string' , 'max:255'],
            // 'product_id' => ['required' , 'integer' , 'exists:products,id'],
            'parent_id' => ['nullable' , 'integer' , 'exists:comments,id'],
            // 'user_id' => $auth,
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
