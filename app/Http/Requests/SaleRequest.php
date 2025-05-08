<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'sale_date' => 'required|date',
            'status' => 'required|string',
            'payment_status' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
            'name_customer' => 'required|string|max:255',
            'phone_customer' => 'required|string|max:15',
        ];
        }

    public function messages()
    {
        return [
            'sale_date.required' => 'Sale date is required.',
            'status.required' => 'Status is required.',
            'payment_status.required' => 'Payment status is required.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a number.',
            'total_amount.min' => 'Total amount must be at least 0.',
        ];
    }
}
