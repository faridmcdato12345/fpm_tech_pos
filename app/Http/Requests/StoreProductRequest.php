<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'product_name' => 'required|unique:products',
            'purchased_price' => 'required',
            'selling_price' => 'required',
            'reorder_level' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'description' => 'nullable',
            'quantity' => 'required',
            'expiration_date' => 'nullable',
            'is_prescription' => 'nullable',
            'sku' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'product_name.unique' => 'This product has been created. Update the product quantity on product page or use the REFILL feature.',
            'product_name.required' => 'Product name is required',
            'category_id.required' => 'Product must have category',
            'unit_id.required' => 'Product must have unit',
            'brand_id.required' => 'Product must have brand'
        ];
    }
}
