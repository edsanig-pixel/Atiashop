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
        // برای اطمینان از اعمال تغییرات، اگر جدول وجود دارد ابتدا حذف شود
        Schema::dropIfExists('parties');

        Schema::create('parties', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique(); // کد حسابداری
    $table->enum('type', ['real', 'legal'])->default('real'); // حقیقی یا حقوقی
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('name'); // نام نمایشی
    $table->string('national_code')->nullable(); // کد ملی یا شناسه ملی
    $table->string('mobile')->nullable();
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->boolean('is_customer')->default(false);
    $table->boolean('is_supplier')->default(false);
    $table->boolean('is_employee')->default(false);
    $table->boolean('status')->default(true); // فعال / غیرفعال
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};