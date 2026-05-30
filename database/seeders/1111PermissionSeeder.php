<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $permissions = [
        'view users',
        'create users',
        'edit users',
        'delete users',

        'view products',
        'create products',
        'edit products',
        'delete products',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $admin = Role::where('name','admin')->first();
    $admin->givePermissionTo(Permission::all());

    $manager = Role::where('name','manager')->first();
    $manager->givePermissionTo([
        'view users',
        'view products',
        'create products',
        'edit products',
    ]);
}
}
