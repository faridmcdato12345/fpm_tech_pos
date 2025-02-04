<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductStockRequest extends FormRequest
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
            'product_id' => ['required'],
            'quantity' => ['required','numeric'],
            'selling_price' => ['required'],
            'purchased_price' => ['required'],
            'expiration_date' => ['nullable'],
        ];
    }
    public function messages()
    {
        return [
            'product_id.required' => 'No product selected.',
            'quantity.required' => 'Restock quantity is required.',
            'quantity.numeric' => 'Restock quantity must be type of number.',
            'selling_price.required' => 'Selling price is required.',
            'purchased_price.required' => 'Purchased price is required.',
        ];
    }
}
