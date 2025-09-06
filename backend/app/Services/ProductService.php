<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * إنشاء منتج جديد (بسيط أو متغير) مع الصور والخيارات والمتغيرات.
     *
     * @param array $data
     * @param array $images
     * @return Product
     */
    public function createProduct(array $data, array $images = []): Product
    {
        // استخدام Transaction لضمان سلامة البيانات
        return DB::transaction(function () use ($data, $images) {

            // 1. إنشاء المنتج الأساسي
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'category_id' => $data['category_id'],
                'product_type' => $data['product_type'],
                // --- منطق ذكي للتعامل مع السعر والمخزون ---
                'price' => $data['product_type'] === 'simple' ? $data['price'] : 0,
                'stock' => $data['product_type'] === 'simple' ? $data['stock'] : 0,
            ]);

            // 2. رفع الصور الأساسية للمنتج
            if (!empty($images)) {
                foreach ($images as $imageFile) {
                    $path = $imageFile->store('products', 'public');
                    $product->images()->create(['image_url' => $path]);
                }
            }

            // 3. التعامل مع المنتج المتغير (هنا يكمن السحر)
            if ($data['product_type'] === 'variable' && isset($data['options'])) {
                
                $createdOptionsValues = []; // لتخزين قيم الخيارات التي تم إنشاؤها

                // --- إنشاء الخيارات والقيم الخاصة بها ---
                foreach ($data['options'] as $optionData) {
                    $option = $product->options()->create(['name' => $optionData['name']]);
                    
                    foreach ($optionData['values'] as $value) {
                        $createdValue = $option->values()->create(['value' => $value]);
                        // تخزين القيمة مع اسمها لتسهيل العثور عليها لاحقًا
                        $createdOptionsValues[strtolower($value)] = $createdValue->id;
                    }
                }

                // --- إنشاء المتغيرات وربطها بالقيم ---
                if (isset($data['variants'])) {
                    foreach ($data['variants'] as $variantData) {
                        $variant = $product->variants()->create([
                            'price' => $variantData['price'],
                            'sku' => $variantData['sku'] ?? null,
                            'stock' => $variantData['stock'],
                        ]);

                        // ربط المتغير بالقيم الصحيحة (مثلاً: أحمر + XL)
                        $valueIds = [];
                        foreach ($variantData['options'] as $optionValue) {
                            if (isset($createdOptionsValues[strtolower($optionValue)])) {
                                $valueIds[] = $createdOptionsValues[strtolower($optionValue)];
                            }
                        }
                        
                        if (!empty($valueIds)) {
                            $variant->optionValues()->sync($valueIds);
                        }
                    }
                }
            }
            
            // إرجاع المنتج مع كل العلاقات الجديدة
            return $product->load('images', 'options.values', 'variants.optionValues');
        });
    }

    /**
     * تحديث منتج حالي. (سنعمل على هذه الدالة لاحقًا)
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // ... سيتم إضافة منطق التحديث هنا في الخطوات القادمة
        $product->update($data);
        return $product;
    }
}