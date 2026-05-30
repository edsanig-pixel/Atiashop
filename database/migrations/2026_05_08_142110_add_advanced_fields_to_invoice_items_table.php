<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            // تخفیف و مالیات در سطح ردیف (در صورت نیاز، ولی مالیات نهایی از روی کل فاکتور حساب می‌شود)
            $table->decimal('discount_percent', 5, 2)->default(0)->after('subtotal');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('discount_percent');
            $table->decimal('tax_percent', 5, 2)->default(0)->nullable()->after('discount_amount');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_percent');
            
            // هزینه‌های جانبی در هر ردیف
            $table->decimal('packing_cost', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('extra_cost', 15, 2)->default(0)->after('packing_cost');
            $table->decimal('staff_cost', 15, 2)->default(0)->after('extra_cost');
            
            // موجودی انبار در لحظه فروش
            $table->integer('stock_at_sale')->default(0)->after('staff_cost');
            
            // امکان ذخیره واحد متفاوت در زمان فروش (اگر کاربر تغییر داد)
            $table->string('unit_name')->nullable()->after('stock_at_sale');
        });
    }

    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn([
                'discount_percent', 'discount_amount', 'tax_percent', 'tax_amount',
                'packing_cost', 'extra_cost', 'staff_cost', 'stock_at_sale', 'unit_name'
            ]);
        });
    }
};