<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

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
        
        // --- هنا التعديل الذكي ---
        // فك تشفير حقول JSON قبل إرسالها للسيرفيس
        if ($request->product_type === 'variable') {
            $validatedData['options'] = json_decode($request->input('options'), true);
            $validatedData['variants'] = json_decode($request->input('variants'), true);
        }

        $images = $request->hasFile('images') ? $request->file('images') : [];
        
        $product = $this->productService->createProduct($validatedData, $images);
        
        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'images', 'options.values', 'variants.optionValues']));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();
        $updatedProduct = $this->productService->updateProduct($product, $validatedData);
        return response()->json($updatedProduct);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}