<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('slug', 'apparel')->first();
        if (!$category) {
            $category = Category::create(['name' => 'ملابس', 'slug' => 'apparel']);
        }

        // --- المنتج الأول: بسيط ---
        Product::create([
            'category_id' => $category->id,
            'name' => 'كتاب فن الحرب',
            'slug' => 'the-art-of-war',
            'short_description' => 'كتاب استراتيجي قديم لا يزال يدرس.',
            'description' => 'كتاب استراتيجي قديم لا يزال يدرس في الكليات العسكرية حول العالم.',
            'price' => 95.50,
            'product_type' => 'simple',
            'stock' => 30,
        ]);

        // --- المنتج الثاني: متغير (تيشيرت) ---
        $tshirt = Product::create([
            'category_id' => $category->id,
            'name' => 'تيشيرت مبرمج محترف',
            'slug' => 'pro-coder-tshirt',
            'short_description' => 'أفضل تيشيرت للمبرمجين، قطن عالي الجودة.',
            'description' => 'أفضل تيشيرت للمبرمجين المحترفين. قطن عالي الجودة.',
            'price' => 150.00,
            'product_type' => 'variable',
            'stock' => 0,
        ]);

        // إنشاء الخيارات والقيم للمنتج الثاني
        $sizeOption = $tshirt->options()->create(['name' => 'القياس']);
        $sizeM = $sizeOption->values()->create(['value' => 'M']);
        $sizeL = $sizeOption->values()->create(['value' => 'L']);

        $colorOption = $tshirt->options()->create(['name' => 'اللون']);
        $colorRed = $colorOption->values()->create(['value' => 'أحمر']);
        
        // إنشاء المتغيرات وربطها بالقيم
        $variant1 = $tshirt->variants()->create(['price' => 150, 'stock' => 20, 'sku' => 'TS-M-RED']);
        $variant1->optionValues()->attach([$sizeM->id, $colorRed->id]);

        $variant2 = $tshirt->variants()->create(['price' => 160, 'stock' => 10, 'sku' => 'TS-L-RED']);
        $variant2->optionValues()->attach([$sizeL->id, $colorRed->id]);
    }
}