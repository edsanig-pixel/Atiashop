<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('نام تیم (مثال: مالی، فروش، انبار)');
            $table->unsignedBigInteger('team_leader_id')->nullable()->comment('سرپرست تیم (ارجاع به users)');
            $table->foreign('team_leader_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};