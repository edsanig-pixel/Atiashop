<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ۱. اصلاح جدول دسته‌بندی
        Schema::table('categories', function (Blueprint $table) {
            // اگر فیلد parent_id وجود ندارد، آن را بساز
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->onDelete('cascade');
            }
            // اصلاح فیلد کد (نام این فیلد در این جدول code است، نه sku)
            $table->string('code', 10)->change();
        });

        // ۲. اصلاح جدول محصولات
        Schema::table('products', function (Blueprint $table) {
            // تغییر طول SKU و ایندکس کردن آن
            $table->string('sku', 20)->index()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};