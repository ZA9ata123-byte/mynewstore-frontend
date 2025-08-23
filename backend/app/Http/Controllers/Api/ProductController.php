<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource for public access.
     */
    public function index()
    {
        // هادي كترجع لائحة المنتجات كاملة، مرتبة من الجديد للقديم
        return Product::latest()->get();
    }

    /**
     * Display the specified resource for public access.
     */
    public function show(Product $product)
    {
        // هادي كترجع منتج واحد بالـ ID ديالو
        return $product;
    }
}