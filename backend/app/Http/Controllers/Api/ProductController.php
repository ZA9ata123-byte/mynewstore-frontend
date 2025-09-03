<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * --- هنا قمنا بالتعديل ---
     * أضفنا withCount('variants') لإرجاع عدد المتغيرات مع كل منتج
     */
    public function index()
    {
        return Product::withCount('variants')->latest()->get();
    }

    /**
     * عرض منتج واحد محدد للعموم.
     */
    public function show(Product $product)
    {
        return $product->load('variants');
    }
}