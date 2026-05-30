<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailed_accounts', function (Blueprint $table) {
            $table->id();
            $table->char('code', 6)->unique()->index()
                  ->comment('کد ۶ رقمی ثابت تفصیلی شناور (صفرهای پیشرو الزامی)');
            $table->string('title', 255);
            $table->enum('type', ['person', 'bank', 'cash', 'cost_center', 'project']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailed_accounts');
    }
};