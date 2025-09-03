<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest; // زدنا هادي
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * حقن السيرفيس فالكونترولر
     */
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * عرض لائحة المنتجات
     */
    public function index()
    {
        $products = Product::with(['category', 'variants', 'images'])
                            ->latest()
                            ->paginate(10);
                            
        return response()->json($products);
    }

    /**
     * تخزين منتج جديد
     */
    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();
        $images = $request->hasFile('images') ? $request->file('images') : [];
        $product = $this->productService->createProduct($validatedData, $images);
        return response()->json($product->load('images', 'options.values', 'variants'), 201);
    }

    /**
     * عرض منتج معين
     */
    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'images', 'options.values', 'variants.optionValues']));
    }

    /**
     * تعديل منتج معين
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $updatedProduct = $this->productService->updateProduct($product, $validatedData);
        return response()->json($updatedProduct);
    }

    /**
     * حذف منتج معين
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}