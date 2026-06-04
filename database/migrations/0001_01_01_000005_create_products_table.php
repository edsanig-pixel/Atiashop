<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->string('sku')->unique();
        $table->decimal('purchase_price', 15, 2);
        $table->decimal('sale_price', 15, 2);
        $table->integer('stock')->default(0);
        $table->boolean('is_active')->default(true)->comment('وضعیت فعال یا غیرفعال بودن کالا برای فروش'); // این ستون اضافه شد
        $table->timestamps();
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

