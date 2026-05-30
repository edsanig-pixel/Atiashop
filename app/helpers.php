<?php

use App\Models\Setting;

function jalali($date)
{
    return \Morilog\Jalali\Jalalian::fromCarbon($date)->format('Y/m/d');
}



if (!function_exists('convert_currency')) {
    function convert_currency($amountInRials)
    {
        $currency = Setting::get('currency', 'ریال');
        if ($currency == 'تومان') {
            return $amountInRials / 10;
        }
        return $amountInRials;
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amountInRials)
    {
        $converted = convert_currency($amountInRials);
        $currency = Setting::get('currency', 'ریال');
        return number_format($converted) . ' ' . $currency;
    }
}