<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- هنا تم الإصلاح: تم حذف حقل الوصف ---
        Category::create(['name' => 'كتب', 'slug' => 'books']);
        Category::create(['name' => 'ملابس', 'slug' => 'apparel']);
        Category::create(['name' => 'إلكترونيات', 'slug' => 'electronics']);
    }
}