<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First, run the seeder for roles and permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a specific admin user
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true, // We can remove this later
        ]);

        // Assign the super-admin role to the admin user
        $admin->assignRole('super-admin');

        // Then, run the other seeders
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}