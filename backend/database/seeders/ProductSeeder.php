<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // <-- مهم نستدعيو الموديل

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name' => 'أساسيات الشطرنج للمبتدئين',
            'description' => 'كتاب رائع لتعلم لعبة الشطرنج من الصفر وبناء أساس قوي.',
            'price' => 120.00,
            'stock_quantity' => 50,
            'image_url' => 'https://i.ibb.co/WpBPbrGN/296352289-352577747070597-7492146128612563083-n.jpg',
        ]);

        Product::create([
            'category_id' => 1,
            'name' => 'فن الحرب',
            'description' => 'كتاب استراتيجي قديم لا يزال يدرس في الكليات العسكرية حول العالم.',
            'price' => 95.50,
            'stock_quantity' => 30,
            'image_url' => 'https://i.ibb.co/bX11L2S/3215.jpg',
        ]);
    }
}