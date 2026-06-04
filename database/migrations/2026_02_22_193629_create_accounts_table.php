<?php

// AFTER
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // برای اتصال حساب معین به کل، و کل به گروه
            $table->enum('level', ['root', 'group', 'general', 'subsidiary'])->default('subsidiary')
                  ->comment('سطح حساب: ریشه(۱رقم)، گروه(۲رقم)، کل(۴رقم)، معین(۶رقم)');
            $table->char('code', 6)->comment('کد حساب با طول ثابت ۶ رقم - صفرهای پیشرو الزامی');
            $table->string('title');
            $table->enum('nature', ['debtor', 'creditor'])->comment('ماهیت حساب: بدهکار یا بستانکار');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // ایجاد رابطه خودارجاعی (Self-referencing Foreign Key) برای درختواره حساب‌ها
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};