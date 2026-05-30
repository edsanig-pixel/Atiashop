<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Party;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    public function render()
    {
        // بهینه‌سازی: دریافت تمام جمع‌ها در یک کوئری واحد
        $stats = Transaction::select('type', DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $sales = $stats['sale'] ?? 0;
        $purchases = $stats['purchase'] ?? 0;

        return view('livewire.dashboard', [
            'totalSales' => $sales,
            'totalPurchases' => $purchases,
            'netProfit' => $sales - $purchases,
            'totalExpenses' => $stats['expense'] ?? 0,
            'partiesCount' => Party::count(),
            'totalUsers' => User::count(), // اضافه شد برای جلوگیری از خطا
            'totalProducts' => Product::count(), // اضافه شد برای جلوگیری از خطا
			//'recentActivities' => Activity::with('causer')->latest()->take(5)->get(),
			'recentActivities' => collect(),
            'systemMonths' => $this->getDynamicMonths(),
        ])->layout('layouts.app');
    }

    private function getDynamicMonths()
    {
        $months = [];
        // ایجاد نام ماه‌های شمسی (در صورت نصب نبودن پکیج خاص، از این مپ سیستمی استفاده می‌شود)
        $map = [1=>'فروردین', 2=>'اردیبهشت', 3=>'خرداد', 4=>'تیر', 5=>'مرداد', 6=>'شهریور', 7=>'مهر', 8=>'آبان', 9=>'آذر', 10=>'دی', 11=>'بهمن', 12=>'اسفند'];
        
        // بر اساس ماه فعلی، ۴ ماه قبل را پیدا می‌کند
        $currentMonthNum = 12; // این مقدار باید از Carbon گرفته شود، برای تست فعلاً ۱۲
        for ($i = 3; $i >= 0; $i--) {
            $m = $currentMonthNum - $i;
            if ($m <= 0) $m += 12;
            $months[] = $map[$m];
        }
        return $months;
    }
}