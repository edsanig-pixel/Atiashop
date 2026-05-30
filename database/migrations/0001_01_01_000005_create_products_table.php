<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('products', function (Blueprint $table) {
        // اضافه کردن فیلد unit_id به عنوان nullable (به صورت پیش‌فرض)
        $table->foreignId('unit_id')->nullable()->constrained()->after('category_id');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['unit_id']);
        $table->dropColumn('unit_id');
    });
}
};

