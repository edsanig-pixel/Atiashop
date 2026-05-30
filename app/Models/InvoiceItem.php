<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'discount_percent',
        'discount_amount',
        'tax_percent',
        'tax_amount',
        'packing_cost',
        'extra_cost',
        'staff_cost',
        'stock_at_sale',
        'unit_name',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}