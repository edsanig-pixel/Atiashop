<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetailedAccount extends Model
{
    protected $table = 'detailed_accounts';

    protected $fillable = [
        'code',
        'title',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * رابطه با آرتیکل‌های حسابداری (به عنوان شخص)
     */
    public function personJournalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'person_detailed_id');
    }

    /**
     * رابطه با آرتیکل‌های حسابداری (به عنوان بانک/صندوق)
     */
    public function bankCashJournalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'bank_cash_detailed_id');
    }

    /**
     * رابطه با آرتیکل‌های حسابداری (به عنوان مرکز هزینه)
     */
    public function costCenterJournalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'cost_center_detailed_id');
    }

    /**
     * رابطه با آرتیکل‌های حسابداری (به عنوان پروژه)
     */
    public function projectJournalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'project_detailed_id');
    }

    /**
     * اسکوپ محلی برای فیلتر بر اساس نوع تفصیلی
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}