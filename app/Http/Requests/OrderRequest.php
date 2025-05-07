<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'nullable|integer',
            'user_id' => 'required|exists:users,id',
            'provider_id' => 'required|exists:providers,id',
            'balance'=> 'nullable|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
            'total_price.required' => 'El precio total es obligatorio.',
            'total_price.numeric' => 'El precio total debe ser un número.',
            'total_price.min' => 'El precio total no puede ser negativo.',
            'status.required' => 'El estado es obligatorio.',
            'status.string' => 'El estado debe ser una cadena de texto.',
            'status.max' => 'El estado no puede tener más de 255 caracteres.',
            'provider_id.required' => 'El proveedor es obligatorio.',
            'provider_id.exists' => 'El proveedor seleccionado no existe.',
            
        ];
    }
}
