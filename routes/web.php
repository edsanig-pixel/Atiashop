<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\PartyManager;
use App\Livewire\ProductManager;
use App\Livewire\CategoryManager;
use App\Livewire\UserManager; // اضافه شد


// صفحه اصلی
Route::get('/', function () {
    return view('auth.login');
});

// تمام مسیرهای نرم‌افزار باید پشت سد احراز هویت باشند
Route::middleware(['auth', 'verified'])->group(function () {

    // ۱. میز کار (داشبورد)
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // ۲. مدیریت اشخاص (طرف حساب‌ها)
    Route::get('/parties', PartyManager::class)->name('parties.index');

    // ۳. انبارداری (محصولات و دسته‌بندی‌ها)
    Route::get('/categories', CategoryManager::class)->name('categories.index');
    Route::get('/products', ProductManager::class)->name('products.index');

    // ۴. مدیریت کاربران و سطوح دسترسی (تبدیل به Livewire)
    // نکته: میان‌افزار permission پکیج Spatie را مستقیماً اینجا اعمال کردیم
    Route::get('/users', UserManager::class)
        ->middleware('permission:view users')
        ->name('users.index');

    // ۵. پروفایل کاربری (فعلاً از کنترلر بریز استفاده میکنیم تا لایووایرش را بسازیم)
    // پروفایل (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	
	// فقط مدیر کل (مثلاً با دسترسی admin) بتواند منوها را جابه‌جا کند
    Route::middleware('can:manage menus')->group(function () {
        Route::get('/system/menus', [App\Http\Controllers\MenuController::class, 'index'])->name('menus.index');

});

Route::get('/inventory/units', \App\Livewire\UnitManager::class)->name('units.index');

});

// فاکتور فروش
Route::get('/invoices/create', \App\Livewire\InvoiceManager::class)->name('invoices.create');

//لیست فاکتور

Route::get('/invoices', \App\Livewire\InvoiceList::class)->name('invoices.index');

Route::get('/invoices/{id}', \App\Livewire\InvoiceShow::class)->name('invoices.show');

//گزارش فروش

Route::get('/reports/sales', \App\Livewire\SalesReport::class)->name('reports.sales');

//تنظیمات

Route::get('/settings/general', \App\Livewire\Settings\GeneralSettings::class)->name('settings.general');

//پرینت
Route::get('/invoices/print/{id}', [App\Http\Controllers\InvoicePrintController::class, 'print'])->name('invoices.print');


require __DIR__.'/auth.php';