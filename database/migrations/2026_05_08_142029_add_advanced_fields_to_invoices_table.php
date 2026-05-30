<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // نوع فاکتور: فروش یا برگشت (برای فاز اول فقط 'sales' ولی برای آینده)
            $table->enum('invoice_type', ['sales', 'return'])->default('sales')->after('id');
            // ارجاع به فاکتور اصلی (برای برگشت)
            $table->foreignId('parent_invoice_id')->nullable()->after('invoice_type')->constrained('invoices')->nullOnDelete();
            
            // شماره فاکتور را بعداً با الگوریتم جدید پر می‌کنیم، فعلاً همان invoice_number را نگه می‌داریم اما فرمتش عوض می‌شود
            // (invoice_number همان فیلد قبلی است، فقط نحوه تولید تغییر می‌کند)
            
            // اطلاعات طرف حساب و تحویل
            $table->string('receiver_name')->nullable()->after('customer_id');
            $table->string('delivery_method')->nullable()->default('internal')->after('receiver_name'); // internal, shipping, pickup
            $table->text('address')->nullable()->after('delivery_method');
            $table->string('phone')->nullable()->after('address');
            $table->string('seller_name')->nullable()->after('phone');
            $table->string('project_code')->nullable()->after('seller_name');
            $table->string('subject')->nullable()->after('project_code');
            $table->string('register_number')->nullable()->after('subject');
            $table->string('order_number')->nullable()->after('register_number');
            $table->string('serial_number')->nullable()->after('order_number');
            
            // فیلدهای مالی و تخفیف
            $table->decimal('delivery_cost', 15, 2)->default(0)->after('total_amount');
            $table->decimal('discount_total', 15, 2)->default(0)->after('delivery_cost');
            $table->decimal('tax_rate', 10, 2)->default(10)->comment('درصد مالیات')->after('discount_total');
            $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_rate');
            $table->decimal('extra_charges_total', 15, 2)->default(0)->after('tax_amount');
            $table->decimal('final_amount', 15, 2)->default(0)->after('extra_charges_total');
            $table->decimal('customer_debt', 15, 2)->default(0)->comment('مانده حساب مشتری (فقط نمایشی)')->after('final_amount');
            
            // وضعیت فاکتور (برای نقدی فعلاً 'paid' بعد از ثبت نقدی کامل)
            $table->enum('status', ['pending', 'paid', 'partially_paid', 'overdue'])->default('paid')->after('customer_debt');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['parent_invoice_id']);
            $table->dropColumn([
                'invoice_type', 'parent_invoice_id', 'receiver_name', 'delivery_method',
                'address', 'phone', 'seller_name', 'project_code', 'subject',
                'register_number', 'order_number', 'serial_number', 'delivery_cost',
                'discount_total', 'tax_rate', 'tax_amount', 'extra_charges_total',
                'final_amount', 'customer_debt', 'status'
            ]);
        });
    }
};