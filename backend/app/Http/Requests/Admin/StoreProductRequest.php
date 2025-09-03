<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For now, we'll allow anyone. We can add authorization logic later.
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
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:simple,variable',
            'price' => 'required_if:product_type,simple|nullable|numeric|min:0',
            'stock' => 'required_if:product_type,simple|nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required_if:product_type,variable|json',
            'variants' => 'required_if:product_type,variable|json',
        ];
    }
}