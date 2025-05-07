<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'purchase_price' => 'nullable|numeric',
            'stock' => 'required|integer|min:0',
            'img_url' => 'file|max:10240',
            'provider_id' => 'nullable',
            'category_id' => 'required',
        ];
    }
            // 'provider_id' => 'required|exists:providers,id',

    public function messages(){
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'provider_id.exists' => 'El proveedor seleccionado no es válido.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
        ];
    }

}
