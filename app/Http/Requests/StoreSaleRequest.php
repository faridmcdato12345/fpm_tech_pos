<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'quantity' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Product is required.',
            'quantity.required' => 'Product quantity is required.',
            'quantity.integer' => 'Invalid format on product quantity.'
        ];
    }
}
