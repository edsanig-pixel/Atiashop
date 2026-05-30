<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class InvoicePrintController extends Controller
{
    public function print($id)
{
    $invoice = Invoice::with('customer', 'items.product')->findOrFail($id);
    
    // دریافت تنظیمات
    $currency = Setting::get('currency', 'ریال'); // 'ریال' یا 'تومان'
    
    // تابع تبدیل مبلغ بر اساس واحد انتخابی
    $convertAmount = function($amountInRials) use ($currency) {
        if ($currency == 'تومان') {
            return $amountInRials / 10;
        }
        return $amountInRials;
    };
    
    $settings = [
        'shop_name' => Setting::get('shop_name'),
        'shop_address' => Setting::get('shop_address'),
        'shop_phone' => Setting::get('shop_phone'),
        'shop_register_number' => Setting::get('shop_register_number'),
        'shop_economic_code' => Setting::get('shop_economic_code'),
        'shop_logo' => Setting::get('shop_logo'),
        'currency' => $currency,
        'footer_text' => Setting::get('invoice_footer_text'),
        'show_logo' => Setting::get('invoice_show_logo', true),
        'show_stamp' => Setting::get('invoice_show_stamp', false),
        'convert' => $convertAmount, // ارسال تابع به ویو
    ];
    
    return view('print.invoice', compact('invoice', 'settings'));
}
}