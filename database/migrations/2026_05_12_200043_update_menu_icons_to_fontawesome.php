<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تبدیل آیکون‌های شکلک به کلاس‌های FontAwesome
        DB::table('menus')->update([
            'icon' => DB::raw("
                CASE
                    WHEN icon = '📊' THEN 'fa-chart-line'
                    WHEN icon = '📦' THEN 'fa-box'
                    WHEN icon = '⚙️' THEN 'fa-cog'
                    WHEN icon = '🧾' THEN 'fa-file-invoice'
                    ELSE icon
                END
            ")
        ]);

        // برای منوهایی که آیکون NULL دارند (و از قبل آیکون FontAwesome ندارند)،
        // می‌توانید یک آیکون پیش‌فرض (مثلاً fa-circle) تنظیم کنید (اختیاری)
        DB::table('menus')
            ->whereNull('icon')
            ->update(['icon' => 'fa-circle']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // برگرداندن به حالت قبل (فقط برای رکوردهایی که تغییر کرده‌اند)
        DB::table('menus')->update([
            'icon' => DB::raw("
                CASE
                    WHEN icon = 'fa-chart-line' THEN '📊'
                    WHEN icon = 'fa-box' THEN '📦'
                    WHEN icon = 'fa-cog' THEN '⚙️'
                    WHEN icon = 'fa-file-invoice' THEN '🧾'
                    ELSE icon
                END
            ")
        ]);

        // اگر آیکون null نبوده و به fa-circle تبدیل شده بود، دوباره null کنید (اختیاری)
        // ولی چون نمی‌دانیم کدام‌ها قبلاً null بودند، بهتر است این خط را ننویسیم.
    }
};