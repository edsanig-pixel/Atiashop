<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول هدر اسناد حسابداری (پیش‌نویس، امضاهای سه‌گانه، شماره موقت و قطعی)
     * هر سند پس از امضای سوم یک شماره قطعی (voucher_number) دریافت می‌کند.
     */
    public function up(): void
    {
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();
            
            // شماره موقت سیستم (همان شناسه اصلی برای رجوع‌های داخلی)
            $table->string('temporary_number', 50)->unique()
                  ->comment('شماره موقت پیش‌نویس (مثل: TMP-140201-0001)');
            
            // شماره قطعی سند حسابداری (فقط پس از امضای سوم پر می‌شود)
            $table->string('voucher_number', 50)->nullable()->unique()
                  ->comment('شماره نهایی سند مالی (مثال: INV-140501-0001)');
            
            // تاریخ صدور سند (میلادی ذخیره می‌شود، اما در ویو به شمسی تبدیل می‌گردد)
            $table->date('issue_date');
            
            // شرح کلی سند (اختیاری)
            $table->text('description')->nullable();
            
            // وضعیت امضا (طبق فرآیند سه‌گانه)
            $table->enum('status', ['draft', 'first_signed', 'second_signed', 'finalized'])
                  ->default('draft')
                  ->comment('وضعیت سند: draft(پیش‌نویس)، first_signed(امضای اول/قرمز)، second_signed(امضای دوم/آبی)، finalized(امضای سوم/سبز)');
            
            // ارجاع به کاربر ایجادکننده
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
            
            // تیم مسئول (برای مدیریت دسترسی به امضاها)
            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->onDelete('restrict');
            
            // لینک به ماژول مبدأ (مثلاً فاکتور فروش، سند دریافت و...)
            $table->nullableMorphs('source');
            
            $table->timestamps();
            
            // ایندکس ترکیبی برای جستجوی سریع وضعیت و تیم
            $table->index(['status', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_vouchers');
    }
};