<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderPayment extends FormRequest
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
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
            'order_id' => 'required|exists:orders,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'El campo monto es obligatorio.',
            'amount.numeric' => 'El campo monto debe ser un número.',
            'amount.min' => 'El campo monto debe ser mayor o igual a 0.',
            'note.string' => 'El campo nota debe ser una cadena de texto.',
            'note.max' => 'El campo nota no puede tener más de 255 caracteres.',
            'order_id.required' => 'El campo pedido es obligatorio.',
            'order_id.exists' => 'El pedido seleccionado no existe.',
            'payment_date.required' => 'El campo fecha de pago es obligatorio.',
            'payment_date.date' => 'El campo fecha de pago debe ser una fecha válida.',
            'payment_method.required' => 'El campo método de pago es obligatorio.',
            'payment_method.string' => 'El campo método de pago debe ser una cadena de texto.',
            'payment_method.max' => 'El campo método de pago no puede tener más de 255 caracteres.',
        ];
    }
}
