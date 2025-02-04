<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name' => 'required',
            'purchased_price' => 'required',
            'selling_price' => 'required',
            'reorder_level' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required',
            'category_id.required' => 'Product must have category',
            'unit_id.required' => 'Product must have unit'
        ];
    }
}
