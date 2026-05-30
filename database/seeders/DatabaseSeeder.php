<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ۱. پاک کردن کش دسترسی‌های Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ۲. تعریف تمام دسترسی‌های سیستم
        $permissions = [
            'view products', 'create products', 'edit products', 'delete products',
            'view users', 'create users', 'edit users', 'delete users',
            'view reports', 'manage accounting', 'manage inventory',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ۳. ساخت تمام نقش‌ها (Roles)
        $adminRole      = Role::firstOrCreate(['name' => 'admin']);
        $managerRole    = Role::firstOrCreate(['name' => 'manager']);
        $accountantRole = Role::firstOrCreate(['name' => 'accountant']);
        $warehouseRole  = Role::firstOrCreate(['name' => 'warehouse']);

        // ۴. اختصاص دسترسی‌ها به هر نقش (دقیق طبق خواسته تو)
        
        // ادمین: همه دسترسی‌ها
        $adminRole->syncPermissions(Permission::all());

        // مدیر: محصولات و کاربران (بدون حذف) + گزارشات
        $managerRole->syncPermissions([
            'view users', 'view products', 'create products', 'edit products', 'view reports'
        ]);

        // حسابدار: گزارشات و امور مالی
        $accountantRole->syncPermissions([
            'view reports', 'manage accounting'
        ]);

        // انباردار: محصولات و مدیریت موجودی
        $warehouseRole->syncPermissions([
            'view products', 'manage inventory'
        ]);

        // ۵. ساخت یوزر ادمین اصلی برای ورود به سیستم
        $user = User::firstOrCreate(
            ['email' => 'edsanig@gmail.com'],
            [
                'name' => 'مدیر سیستم',
                'password' => Hash::make('123456'),
            ]
        );

        // متصل کردن یوزر به رول ادمین
        $user->assignRole($adminRole);

        $this->command->info('hame naghsh ha (Admin, Manager, Accountant, Warehouse) dorost shodand! user admin : <edsanig@gmail.com> pass: <123456>   movafagh bashi :)');
    }
}