<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductDetailRequest extends FormRequest
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
            'barcode' => 'nullable',
            'expiration_date' => 'nullable',
            'quantity' => 'required',
            'product_id' => 'required'
        ];
    }

    public function message()
    {
        return [
            'quantity.requried' => 'Product quantity is required.'
        ];
    }
}
