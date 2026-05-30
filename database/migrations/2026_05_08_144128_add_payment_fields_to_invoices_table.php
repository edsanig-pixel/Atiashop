<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'credit', 'check'])->default('cash')->after('final_amount');
            $table->decimal('paid_amount', 15, 2)->default(0)->after('payment_type');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'paid_amount']);
        });
    }
};