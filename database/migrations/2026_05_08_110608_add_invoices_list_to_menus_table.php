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
        Schema::table('menus', function (Blueprint $table) {
            DB::table('menus')->insert([
    'title' => 'لیست فاکتورها',
    'route' => 'invoices.index',
    'icon' => '🧾',
    'parent_id' => null,
    'order' => 6,
    'created_at' => now(),
]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            //
        });
    }
};
