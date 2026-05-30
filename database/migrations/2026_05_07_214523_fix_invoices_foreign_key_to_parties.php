<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ابتدا کلید خارجی قدیمی را حذف می‌کنیم
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        // حالا کلید خارجی جدید به parties اضافه می‌کنیم
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('parties')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        // برای بازگشت به حالت قبل
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }
};