<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalItem extends Model
{
    protected $table = 'journal_items';

    protected $fillable = [
        'journal_voucher_id',
        'account_id',
        'person_detailed_id',
        'bank_cash_detailed_id',
        'cost_center_detailed_id',
        'project_detailed_id',
        'debit',
        'credit',
        'row_description',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    /**
     * رابطه: سند حسابداری والد
     */
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(JournalVoucher::class, 'journal_voucher_id');
    }

    /**
     * رابطه: حساب معین
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * رابطه: تفصیلی شناور (شخص)
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(DetailedAccount::class, 'person_detailed_id');
    }

    /**
     * رابطه: تفصیلی شناور (بانک/صندوق)
     */
    public function bankCash(): BelongsTo
    {
        return $this->belongsTo(DetailedAccount::class, 'bank_cash_detailed_id');
    }

    /**
     * رابطه: تفصیلی شناور (مرکز هزینه)
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(DetailedAccount::class, 'cost_center_detailed_id');
    }

    /**
     * رابطه: تفصیلی شناور (پروژه)
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(DetailedAccount::class, 'project_detailed_id');
    }
}