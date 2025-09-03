<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * Create a new product with its images, options, and variants.
     *
     * @param array $data The validated data from the request.
     * @param array<UploadedFile> $images The uploaded image files.
     * @return Product
     */
    public function createProduct(array $data, array $images = []): Product
    {
        return DB::transaction(function () use ($data, $images) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'short_description' => $data['short_description'] ?? null,
                'price' => $data['price'] ?? 0,
                'stock' => $data['stock'] ?? 0,
                'category_id' => $data['category_id'],
                'product_type' => $data['product_type'],
            ]);

            $this->handleImageUploads($product, $images);

            if ($product->isVariable() && !empty($data['options']) && !empty($data['variants'])) {
                $this->createOptionsAndVariants(
                    $product,
                    json_decode($data['options'], true),
                    json_decode($data['variants'], true)
                );
            }

            return $product;
        });
    }

    /**
     * Update an existing product.
     *
     * @param Product $product The product to update.
     * @param array $data The validated data from the request.
     * @return Product
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $product->update($data);

            // TODO: Add logic to update images, options, and variants later.
            
            return $product;
        });
    }

    /**
     * Handle the uploading and association of images to a product.
     */
    private function handleImageUploads(Product $product, array $images): void
    {
        foreach ($images as $imageFile) {
            $path = $imageFile->store('products', 'public');
            $product->images()->create(['image_url' => $path]);
        }
    }

    /**
     * Create options, values, and variants for a variable product.
     */
    private function createOptionsAndVariants(Product $product, array $optionsData, array $variantsData): void
    {
        foreach ($optionsData as $optionInput) {
            $option = $product->options()->create(['name' => $optionInput['name']]);
            foreach ($optionInput['values'] as $valueInput) {
                $option->values()->create(['value' => $valueInput['value']]);
            }
        }

        $valueMap = $product->fresh()->load('options.values')
            ->options
            ->flatMap(fn($opt) => $opt->values)
            ->pluck('id', 'value');

        foreach ($variantsData as $variantInput) {
            $variant = $product->variants()->create([
                'price' => $variantInput['price'],
                'sku' => $variantInput['sku'],
                'stock' => $variantInput['stock'],
            ]);

            $valueIds = collect($variantInput['combination'])
                ->map(fn($val) => $valueMap[$val] ?? null)
                ->filter();

            if ($valueIds->isNotEmpty()) {
                $variant->optionValues()->attach($valueIds);
            }
        }
    }
}