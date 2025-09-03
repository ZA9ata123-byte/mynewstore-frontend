<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'api'; // الحارس الصحيح هو 'api'

        Permission::create(['guard_name' => $guardName, 'name' => 'manage products']);
        Permission::create(['guard_name' => $guardName, 'name' => 'manage categories']);
        Permission::create(['guard_name' => $guardName, 'name' => 'manage orders']);
        Permission::create(['guard_name' => $guardName, 'name' => 'manage users']);

        Role::create(['guard_name' => $guardName, 'name' => 'user']);
        $adminRole = Role::create(['guard_name' => $guardName, 'name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
// php artisan db:seed --class=RolesAndPermissionsSeeder