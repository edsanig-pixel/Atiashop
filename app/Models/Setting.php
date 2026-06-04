<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // اگر نوع داده در دیتابیس json بود، آن را مجدداً به آرایه PHP تبدیل کن
        if ($setting->type === 'json' || $setting->type === 'array') {
            return json_decode($setting->value, true);
        }

        // اگر نوع داده بولین (بله/خیر) بود، آن را به مقدار واقعی true/false تبدیل کن
        if ($setting->type === 'boolean') {
            return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
        }

        return $setting->value;
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general')
    {
        // اگر مقدار ارسالی آرایه یا شیء بود، یا نوع آن json تعیین شده بود
        if ($type === 'json' || is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            $type = 'json';
        }

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }
}