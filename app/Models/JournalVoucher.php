<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalVoucher extends Model
{
    protected $table = 'journal_vouchers';

    protected $fillable = [
        'temporary_number',
        'voucher_number',
        'issue_date',
        'description',
        'status',
        'created_by',
        'team_id',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * رابطه: آرتیکل‌های این سند
     */
    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'journal_voucher_id');
    }

    /**
     * رابطه: کاربر ایجادکننده سند
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * رابطه: تیم مسئول
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * رابطه پلی‌مورفیک با منبع (فاکتور، سند دریافت/پرداخت و...)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * اسکوپ محلی برای فیلتر بر اساس وضعیت امضا
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}