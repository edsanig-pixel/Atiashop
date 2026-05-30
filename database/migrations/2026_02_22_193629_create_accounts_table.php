<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index(); // کد عددی (مثلا 10101)
            $table->string('name'); // نام حساب (مثلا موجودی نقد و بانک)
            $table->integer('level'); // سطح: 1 (گروه)، 2 (کل)، 3 (معین)
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('accounts');
    }
};