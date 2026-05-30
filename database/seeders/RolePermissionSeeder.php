<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // پاک کردن کش دسترسی‌ها
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // لیست تمام دسترسی‌ها
        $permissions = [
            'view products', 'create products', 'edit products', 'delete products',
            'view users', 'create users', 'edit users', 'delete users',
            'view reports', 'manage accounting', 'manage inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ساخت رول‌ها و اختصاص دسترسی
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all()); // ادمین همه دسترسی‌ها را دارد

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions(['view products', 'create products', 'edit products', 'view reports']);

        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->syncPermissions(['view reports', 'manage accounting']);

        $warehouse = Role::firstOrCreate(['name' => 'warehouse']);
        $warehouse->syncPermissions(['view products', 'manage inventory']);
    }
}