<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_items', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('journal_voucher_id');
            $table->foreign('journal_voucher_id')
                  ->references('id')
                  ->on('journal_vouchers')
                  ->onDelete('cascade');
            
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('restrict');
            
            // ========== تفصیلی‌های شناور چندگانه (طبق اصلاحیه مربی) ==========
            $table->unsignedBigInteger('person_detailed_id')->nullable();
            $table->foreign('person_detailed_id')
                  ->references('id')
                  ->on('detailed_accounts')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('bank_cash_detailed_id')->nullable();
            $table->foreign('bank_cash_detailed_id')
                  ->references('id')
                  ->on('detailed_accounts')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('cost_center_detailed_id')->nullable();
            $table->foreign('cost_center_detailed_id')
                  ->references('id')
                  ->on('detailed_accounts')
                  ->onDelete('restrict');
            
            $table->unsignedBigInteger('project_detailed_id')->nullable();
            $table->foreign('project_detailed_id')
                  ->references('id')
                  ->on('detailed_accounts')
                  ->onDelete('restrict');
            
            // مبالغ
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('row_description', 500)->nullable();
            
            $table->timestamps();
            
            // ایندکس‌های ترکیبی برای سرعت گزارش‌گیری
            $table->index(['account_id', 'person_detailed_id']);
            $table->index(['account_id', 'cost_center_detailed_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_items');
    }
};