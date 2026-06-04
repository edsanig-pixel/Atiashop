<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('restrict'); // ✅ اصلاح: جلوگیری از حذف زنجیره‌ای
            
            $table->enum('level', ['root', 'group', 'general', 'subsidiary'])
                  ->default('subsidiary')
                  ->comment('سطح حساب: ریشه(۱رقم)، گروه(۲رقم)، کل(۴رقم)، معین(۶رقم)');
            
            $table->char('code', 6)->unique()->index()
                  ->comment('کد حساب با طول ثابت ۶ رقم - صفرهای پیشرو الزامی');
            
            $table->string('title', 255);
            $table->enum('nature', ['debtor', 'creditor']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};