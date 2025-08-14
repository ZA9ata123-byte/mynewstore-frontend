<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index()
    {
        // We use 'with' to load the relationships efficiently to avoid N+1 problem
        return Product::with(['category', 'variants'])->get();
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Laravel's Route Model Binding finds the product for us.
        // We just need to load its relationships.
        return $product->load(['variants', 'images', 'category']);
    }

    /**
     * Display a listing of products that belong to a specific category.
     */
    public function productsByCategory(Category $category)
    {
        // Laravel finds the category by its slug.
        // Then we get all products related to it.
        return $category->products()->with(['variants'])->get();
    }
}