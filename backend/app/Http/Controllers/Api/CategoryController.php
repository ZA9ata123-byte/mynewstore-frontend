<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories for the storefront.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Fetch all categories without pagination for the storefront
        $categories = Category::all();
        return response()->json(['data' => $categories]);
    }

    /**
     * Display a single category along with its products.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        // Load the category with its products, paginated
        $products = $category->products()->paginate(12);
        
        return response()->json([
            'category' => $category,
            'products' => $products,
        ]);
    }
}