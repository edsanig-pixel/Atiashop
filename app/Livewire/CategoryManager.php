<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $name, $parent_id, $categories;
	public $showDeleteModal = false; // ✅ اضافه شده
public $deleteId = null; // ✅ اضافه شده

    protected $rules = [
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ];

    public function render()
    {
        // لود کردن دسته‌های اصلی به همراه فرزندان
        $this->categories = Category::with('children')->whereNull('parent_id')->get();
        return view('livewire.category-manager')->layout('layouts.app');
    }

    public function store()
    {
        $this->validate();

        // منطق تولید کد اتوماتیک که قبلاً داشتی
        $lastCategory = Category::where('parent_id', $this->parent_id)
            ->orderBy('code', 'desc')
            ->first();

        if (!$lastCategory) {
            $newCode = '01';
        } else {
            $newCode = str_pad(intval($lastCategory->code) + 1, 2, '0', STR_PAD_LEFT);
        }

        if (intval($newCode) > 99) {
            session()->flash('error', 'سقف تعریف در این سطح پر شده است.');
            return;
        }

        Category::create([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'code' => $newCode,
        ]);

        $this->reset(['name', 'parent_id']);
        session()->flash('status', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        if ($category->children()->count() > 0) {
            session()->flash('error', 'این دسته دارای زیرمجموعه است!');
            return;
        }
        $category->delete();
        session()->flash('status', 'حذف انجام شد.');
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

