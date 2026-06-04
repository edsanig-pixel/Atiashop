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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->decimal('total_amount', 15, 2)->default(0);
        $table->timestamps();
        $table->softDeletes(); // این متد ستون deleted_at را برای حذف نرم به جدول اضافه می‌کند
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

