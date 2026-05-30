<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_type',
        'parent_invoice_id',
        'receiver_name',
        'delivery_method',
        'address',
        'phone',
        'seller_name',
        'project_code',
        'subject',
        'register_number',
        'order_number',
        'serial_number',
        'total_amount',
        'delivery_cost',
        'discount_total',
        'tax_rate',
        'tax_amount',
        'extra_charges_total',
        'final_amount',
        'customer_debt',
        'status',
        'payment_type',
        'due_date',
        'check_number',
        'check_due_date',
        'paid_amount',
    ];

    protected $casts = [
        'due_date' => 'date',
        'check_due_date' => 'date',
        'created_at' => 'datetime',
    ];

    // رابطه با مشتری (طرف حساب)
    public function customer()
    {
        return $this->belongsTo(Party::class, 'customer_id');
    }

    // آیتم‌های فاکتور
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // مقدار باقی‌مانده از پرداخت (برای فاکتورهای غیرنقدی)
    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - ($this->paid_amount ?? 0);
    }

    // بروزرسانی وضعیت بر اساس مبلغ پرداختی و تاریخ سررسید
    public function updateStatus()
    {
        if ($this->payment_type == 'cash') {
            $this->status = 'paid';
        } elseif ($this->paid_amount >= $this->total_amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->due_date && now()->greaterThan($this->due_date)) {
            $this->status = 'overdue';
        } else {
            $this->status = 'pending';
        }
        $this->saveQuietly();
    }
}