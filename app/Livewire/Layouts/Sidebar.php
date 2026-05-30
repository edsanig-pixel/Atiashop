<?php

namespace App\Livewire\Layouts; // مربی: حتما چک کن این خط همین باشد

use Livewire\Component;
use App\Models\Menu;

class Sidebar extends Component
{
    public function render()
    {
        // ما منوها را از دیتابیس می‌گیریم
        $menus = Menu::whereNull('parent_id')->orderBy('order')->get();

        // مربی: اینجا آدرس دقیق فایل Blade را می‌دهیم
        return view('livewire.layouts.sidebar', [
            'menus' => $menus
        ]);
    }
}