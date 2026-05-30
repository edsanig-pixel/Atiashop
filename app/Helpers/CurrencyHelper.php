<?php

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    public static function convert($amountInRials)
    {
        $unit = Setting::get('currency', 'ریال');
        if ($unit == 'تومان') {
            return $amountInRials / 10;
        }
        return $amountInRials;
    }
    
    public static function format($amountInRials)
    {
        $converted = self::convert($amountInRials);
        $unit = Setting::get('currency', 'ریال');
        return number_format($converted) . ' ' . $unit;
    }
}