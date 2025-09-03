<?php

namespace App\Http\Requests\Admin;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'sometimes|required|exists:categories,id',
            'product_type' => 'sometimes|required|in:simple,variable',
            'price' => 'sometimes|nullable|numeric|min:0',
            'stock' => 'sometimes|nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'nullable|json',
            'variants' => 'nullable|json',
        ];
    }
}