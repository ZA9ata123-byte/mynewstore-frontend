<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Let's create two categories
        $category1 = Category::create(['name' => 'كتب', 'slug' => 'kotob']);
        $category2 = Category::create(['name' => 'إلكترونيات', 'slug' => 'electroniyat']);

        // Create a product in the 'كتب' category
        $product1 = Product::create([
            'category_id' => $category1->id,
            'name' => 'أساسيات الشطرنج للمبتدئين',
            'description' => 'كتاب رائع لتعلم لعبة الشطرنج من الصفر وبناء أساس قوي.',
            'image_url' => 'https://i.ibb.co/WpBPbrGN/296352289-352577747070597-7492146128612563083-n.jpg'
        ]);

        // Add a variant (price, stock) to the product
        $product1->variants()->create([
            'price' => '120.00',
            'stock_quantity' => 50,
            'attributes' => []
        ]);
        
        // You can add more products here if you want
    }
}