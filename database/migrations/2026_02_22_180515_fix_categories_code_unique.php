<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('categories', function (Blueprint $table) {
        // ابتدا ایندکس قبلی را حذف کن
        $table->dropUnique(['code']); 
        // حالا یک ایندکس ترکیبی بساز: کد + والد با هم باید یکتا باشند
        $table->unique(['code', 'parent_id']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
