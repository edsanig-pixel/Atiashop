<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    /**
     * نام جدول مرتبط
     */
    protected $table = 'accounts';

    /**
     * فیلدهای قابل پر شدن (Mass Assignment)
     */
    protected $fillable = [
        'parent_id',
        'level',
        'code',
        'title',
        'nature',
        'is_active',
    ];

    /**
     * تبدیل انواع داده (Casting)
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * رابطه: والد این حساب (برای ساختار درختی)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    /**
     * رابطه: فرزندان این حساب (زیرمجموعه‌ها)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    /**
     * رابطه: کلیه آرتیکل‌های حسابداری که به این حساب ارجاع داده‌اند
     */
    public function journalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'account_id');
    }

    /**
     * دریافت حساب‌های فعال (اسکوپ محلی)
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}