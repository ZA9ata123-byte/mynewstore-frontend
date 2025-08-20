<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // <-- مهم نستدعيو الموديل

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'كتب',
            'description' => 'قسم خاص بالكتب التعليمية والثقافية.'
        ]);

        Category::create([
            'name' => 'إلكترونيات',
            'description' => 'قسم خاص بالأجهزة الإلكترونية.'
        ]);
    }
}