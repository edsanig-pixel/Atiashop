<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('menus', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // نام منو مثل: مدیریت کالا
        $table->string('route')->nullable(); // نام روت مثل: products.index
        $table->string('icon')->nullable(); // اموجی یا کلاس آیکون
        $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
        $table->integer('order')->default(0); // ترتیب نمایش
        $table->string('permission')->nullable(); // سطح دسترسی لازم
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
