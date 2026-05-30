<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // اضافه کردن آیتم منوی «ثبت فاکتور جدید»
        DB::table('menus')->insert([
            'title' => 'ثبت فاکتور جدید',
            'route' => 'invoices.create',
            'icon' => '🧾',
            'parent_id' => null,       // اگر می‌خواهی زیرمنوی چیز دیگری باشد، عدد parent_id را وارد کن
            'order' => 5,              // ترتیب نمایش (می‌توانی تنظیم کنی)
            'permission' => null,      // یا اگر نیاز به مجوز خاصی دارد بگذار مثلاً 'create invoices'
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        // حذف آیتم منو در صورت rollback
        DB::table('menus')->where('route', 'invoices.create')->delete();
    }
};