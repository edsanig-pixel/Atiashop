<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // تنظیمات فروشگاه
        Setting::set('shop_name', 'فروشگاه آنلاین من', 'text', 'general');
        Setting::set('shop_address', 'تهران، خیابان ...', 'text', 'general');
        Setting::set('shop_phone', '021-12345678', 'text', 'general');
        Setting::set('shop_register_number', '12345', 'text', 'general');
        Setting::set('shop_economic_code', '123456789', 'text', 'general');
        Setting::set('shop_logo', null, 'file', 'general');
        Setting::set('currency', 'تومان', 'text', 'general');

        // تنظیمات فاکتور و مالیات
        Setting::set('tax_enabled', true, 'boolean', 'invoice');
        Setting::set('tax_rate', 10, 'number', 'invoice');
        Setting::set('discount_enabled', true, 'boolean', 'invoice');
        Setting::set('invoice_footer_text', 'با تشکر از شما', 'text', 'invoice');
        Setting::set('invoice_print_size', 'A4', 'text', 'invoice');
        Setting::set('invoice_show_logo', true, 'boolean', 'invoice');
        Setting::set('invoice_show_stamp', false, 'boolean', 'invoice');

        // مدیریت کارت‌های داشبورد
        Setting::set('dashboard_cards', json_encode(['total_sales', 'receivables', 'debts', 'proformas']), 'json', 'dashboard');
    }
}