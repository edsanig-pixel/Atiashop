<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = ['name', 'code', 'parent_id'];

    // نمایش فرزندان
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // نمایش والد
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // رابطه با محصولات
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}