<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('account_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 15, 2);
        // اضافه کردن ستون نوع تراکنش برای تفکیک بدهکار و بستانکار در داشبورد
        $table->enum('type', ['debit', 'credit'])->comment('نوع تراکنش: بدهکار (debit) یا بستانکار (credit)');
        $table->string('description')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};