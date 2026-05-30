<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['title', 'route', 'icon', 'parent_id', 'order', 'permission'];
public function render()
{
    return view('livewire.layouts.sidebar', [
        // لود کردن منوهای اصلی به همراه زیرمنوهایشان
        'menus' => \App\Models\Menu::whereNull('parent_id')->with('children')->orderBy('order')->get()
    ]);
}
    // رابطه برای گرفتن زیرمنوها
    public function children() {
    return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
}
}