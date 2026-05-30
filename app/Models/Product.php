<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\StockMovement;
use App\Models\Category;

class Product extends Model
{
	
	public function stockMovements()
{
    return $this->hasMany(StockMovement::class);
}

	use LogsActivity;

public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logAll()
        ->useLogName('product')
        ->logOnlyDirty();
}

    protected $fillable = [
	'category_id', 
	'unit_id', 
	'name', 
	'sku', 
	'purchase_price', 
	'sale_price', 
	'stock'
	];

public function getCreatedAtJalaliAttribute()
{
    return \Morilog\Jalali\Jalalian::fromCarbon($this->created_at)->format('Y/m/d');
}

public function category()
{
    return $this->belongsTo(Category::class);
}

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
	
	public static function generateSku($categoryId)
{
    $category = \App\Models\Category::with('parent')->find($categoryId);
    
    // کد والد (مثلا 01) + کد خودش (مثلا 01)
    $prefix = $category->parent->code . $category->code; 

    // پیدا کردن آخرین شماره سریال در این دسته
    $lastProduct = self::where('category_id', $categoryId)->latest()->first();
    $nextSerial = $lastProduct ? (intval(substr($lastProduct->sku, -5)) + 1) : 1;
    
    // ترکیب نهایی: 01 + 01 + 00001
    return $prefix . str_pad($nextSerial, 5, '0', STR_PAD_LEFT);
}

public function getFullCodeAttribute() {
    // رقم اول (1) + کد والد (01) + کد دسته (01) + سریال (0001)
    return "1" . $this->category->parent->code . $this->category->code . str_pad($this->id, 4, '0', STR_PAD_LEFT);
}

public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
	

}


