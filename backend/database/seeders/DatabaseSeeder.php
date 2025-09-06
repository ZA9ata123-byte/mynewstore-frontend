<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- زدنا هادي

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- هنا تمت إضافة المنطق الذكي لإنشاء الأدمن ---

        // 1. إنشاء مستخدم عادي (زبون)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'), // كلمة السر هي password
            'is_admin' => false, // هذا مستخدم عادي
        ]);

        // 2. إنشاء حساب المدير (Admin)
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // كلمة السر هي password
            'is_admin' => true, // هذا هو المدير!
        ]);
        
        // 3. استدعاء باقي الـ Seeders
        $this->call([
            RolesAndPermissionsSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
