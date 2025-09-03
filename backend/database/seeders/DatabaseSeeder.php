<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. استدعاء ملف الصلاحيات أولاً لإنشاء الأدوار (admin, user)
        $this->call(RolesAndPermissionsSeeder::class);

        // --- هنا تمت الإضافة والتصحيح ---
        $guardName = 'api';

        // 2. إنشاء مستخدم أدمن بالمعلومات الصحيحة
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // كلمة المرور هي 'password'
            'is_admin' => true,
        ])->assignRole(Role::findByName('admin', $guardName));

        // 3. (اختياري) إنشاء مستخدم عادي للاختبارات
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // كلمة المرور هي 'password'
        ])->assignRole(Role::findByName('user', $guardName));


        // 4. استدعاء باقي الملفات لتعمير قاعدة البيانات
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
    }
}