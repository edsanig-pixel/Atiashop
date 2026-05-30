<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class GeneralSettings extends Component
{
    use WithFileUploads;

    // عمومی
    public $shop_name, $shop_address, $shop_phone, $shop_register_number, $shop_economic_code, $currency;
    public $logo;

    // فاکتور و چاپ
    public $tax_enabled, $tax_rate, $discount_enabled, $invoice_footer_text, $invoice_print_size, $invoice_show_logo, $invoice_show_stamp;

    // داشبورد
    public $dashboard_cards = [];

    // برای تب پشتیبان‌گیری
    public $restoreFile;

    protected $cardOptions = [
        'total_sales' => 'کل فروش',
        'receivables' => 'مطالبات',
        'debts' => 'بدهی‌ها',
        'proformas' => 'پیش‌فاکتورها',
        'total_purchases' => 'کل خرید',
        'total_expenses' => 'کل هزینه‌ها',
    ];

    public function mount()
    {
        $this->shop_name = Setting::get('shop_name');
        $this->shop_address = Setting::get('shop_address');
        $this->shop_phone = Setting::get('shop_phone');
        $this->shop_register_number = Setting::get('shop_register_number');
        $this->shop_economic_code = Setting::get('shop_economic_code');
        $this->currency = Setting::get('currency');
        $this->tax_enabled = Setting::get('tax_enabled', true);
        $this->tax_rate = Setting::get('tax_rate', 10);
        $this->discount_enabled = Setting::get('discount_enabled', true);
        $this->invoice_footer_text = Setting::get('invoice_footer_text');
        $this->invoice_print_size = Setting::get('invoice_print_size', 'A4');
        $this->invoice_show_logo = Setting::get('invoice_show_logo', true);
        $this->invoice_show_stamp = Setting::get('invoice_show_stamp', false);
        $this->dashboard_cards = Setting::get('dashboard_cards', []);
    }

    public function save()
    {
        Setting::set('shop_name', $this->shop_name);
        Setting::set('shop_address', $this->shop_address);
        Setting::set('shop_phone', $this->shop_phone);
        Setting::set('shop_register_number', $this->shop_register_number);
        Setting::set('shop_economic_code', $this->shop_economic_code);
        Setting::set('currency', $this->currency);
        Setting::set('tax_enabled', $this->tax_enabled, 'boolean');
        Setting::set('tax_rate', $this->tax_rate, 'number');
        Setting::set('discount_enabled', $this->discount_enabled, 'boolean');
        Setting::set('invoice_footer_text', $this->invoice_footer_text);
        Setting::set('invoice_print_size', $this->invoice_print_size);
        Setting::set('invoice_show_logo', $this->invoice_show_logo, 'boolean');
        Setting::set('invoice_show_stamp', $this->invoice_show_stamp, 'boolean');
        Setting::set('dashboard_cards', $this->dashboard_cards, 'json');

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            Setting::set('shop_logo', $path, 'file');
        }

        session()->flash('message', 'تنظیمات با موفقیت ذخیره شد.');
    }

    public function backup()
    {
        // ساده: خروجی SQL کل دیتابیس (با استفاده از mysqldump)
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        \Illuminate\Support\Facades\File::ensureDirectoryExists(storage_path('app/backups'));
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            return response()->download($path)->deleteFileAfterSend(true);
        }
        session()->flash('error', 'خطا در تهیه پشتیبان. ممکن است mysqldump نصب نباشد.');
    }

    public function restore()
    {
        $this->validate(['restoreFile' => 'required|file|mimes:sql,txt']);
        $file = $this->restoreFile->getRealPath();
        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s %s < %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $file
        );
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            session()->flash('message', 'بازیابی با موفقیت انجام شد.');
        } else {
            session()->flash('error', 'خطا در بازیابی. فایل معتبر نیست یا مشکل دیتابیس دارد.');
        }
    }

    public function render()
    {
        return view('livewire.settings.general-settings', [
            'cardOptions' => $this->cardOptions,
        ])->layout('layouts.app');
    }
}