<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // هادي كتجيب لينا كاع الأقسام
    public function index()
    {
        return Category::latest()->get();
    }

    // هادي كتزيد قسم جديد (للمدير فقط)
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return response()->json($category, 201);
    }
}