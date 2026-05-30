<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // تعریف دسترسی‌های ریز برای ماژول اشخاص
        $permissions = [
            'view-parties',
            'create-parties',
            'edit-parties',
            'delete-parties',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}