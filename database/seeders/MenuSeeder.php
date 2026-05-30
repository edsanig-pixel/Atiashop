<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // پاکسازی داده‌های قبلی برای جلوگیری از تکرار
        Menu::truncate();

        // ۱. منوی میز کار (بدون زیرمنو)
        Menu::create([
            'title' => 'میز کار (داشبورد)',
            'route' => 'dashboard',
            'icon' => '📊',
            'order' => 1,
        ]);

        // ۲. منوی اصلی عملیات کالا (دارای زیرمنو)
        $inventory = Menu::create([
            'title' => 'عملیات کالا و انبار',
            'icon' => '📦',
            'order' => 2,
        ]);

        Menu::create([
            'title' => 'لیست محصولات',
            'route' => 'products.index',
            'parent_id' => $inventory->id,
            'order' => 1,
        ]);

        Menu::create([
            'title' => 'دسته‌بندی‌ها',
            'route' => 'categories.index',
            'parent_id' => $inventory->id,
            'order' => 2,
        ]);

        // ۳. منوی تعاریف پایه
        $baseData = Menu::create([
            'title' => 'تعاریف پایه',
            'icon' => '⚙️',
            'order' => 3,
        ]);

        Menu::create([
            'title' => 'طرف حساب‌ها',
            'route' => 'parties.index',
            'parent_id' => $baseData->id,
            'order' => 1,
        ]);

        Menu::create([
            'title' => 'دسترسی کاربران',
            'route' => 'users.index',
            'parent_id' => $baseData->id,
            'order' => 2,
            'permission' => 'view users', // اگر از Spatie استفاده می‌کنی
        ]);
		
		Menu::create([
    'title' => 'واحدهای کالا',
    'route' => 'units.index', // نام روتی که در web.php تعریف کردیم
    'parent_id' => $baseData->id, // آی‌دی منوی "تعاریف پایه" یا "عملیات کالا"
    'order' => 3,
]);
    }
}