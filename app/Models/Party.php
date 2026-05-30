<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
   protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'name',
        'code',
        'mobile',
        'national_code',
        'address',
        'is_customer',
        'is_supplier',
        'is_employee',
        'status',
    ];

    /**
     * متد هوشمند برای تولید خودکار کد تفصیلی (۴ رقمی)
     * این متد آخرین کد را در دیتابیس پیدا کرده و یکی به آن اضافه می‌کند.
     */
    public static function generateNextCode()
    {
        // پیدا کردن آخرین رکورد ثبت شده بر اساس کد
        $lastRecord = self::orderBy('code', 'desc')->first();

        if (!$lastRecord) {
            return '0001'; // اگر اولین نفر است
        }

        // تبدیل کد رشته‌ای به عدد، اضافه کردن یک واحد و تبدیل مجدد به رشته ۴ رقمی
        $nextNumber = intval($lastRecord->code) + 1;
        
        // تولید کد با صفرهای پشت عدد (مثلاً 0002)
        return str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}