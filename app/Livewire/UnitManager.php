<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unit;

class UnitManager extends Component
{
    public $title, $symbol, $showModal = false; // متغیر شو مدال اضافه شد
	public $showDeleteModal = false; // ✅ اضافه شده
public $deleteId = null; // ✅ اضافه شده

    protected $rules = [
        'title' => 'required|min:2',
        'symbol' => 'nullable|max:10',
    ];

    public function save()
    {
        $this->validate([
        'title' => 'required|min:2',
        'symbol' => 'nullable|max:10',
    ]);

    Unit::create([
        'title' => $this->title,
        'symbol' => $this->symbol,
    ]);

    $this->reset(['title', 'symbol', 'showModal']); // بستن مودال و پاک کردن فرم
    session()->flash('message', 'واحد با موفقیت ثبت شد.');
    }

    public function render()
{
    return view('livewire.unit-manager', [
        'units' => \App\Models\Unit::latest()->get()
    ])->layout('layouts.app');
}

public function delete($id)
{
    $unit = \App\Models\Unit::findOrFail($id);
    $unit->delete();
    
    // مربی: ارسال پیام موفقیت به کاربر
    session()->flash('message', 'واحد با موفقیت حذف شد.');
}

// این متد را اضافه کنید
public function openDeleteModal($id)
{
	
    $this->deleteId = $id;
    $this->showDeleteModal = true;
	//dd($this->showDeleteModal);
}

// این متد را اضافه کنید
public function confirmDelete()
{
    $this->delete($this->deleteId);
    $this->showDeleteModal = false;
}

}