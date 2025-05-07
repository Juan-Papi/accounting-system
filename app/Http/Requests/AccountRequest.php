<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'is_parent' => 'required|boolean',
            'parent_account_id' => 'nullable|exists:accounting_accounts,id',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'The code field is required.',
            'name.required' => 'The name field is required.',
            'type.required' => 'The type field is required.',
            'is_parent.required' => 'The is parent field is required.',
            'parent_account_id.exists' => 'The selected parent account does not exist.',
        ];
    }
}

