<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $category_id, $purchase_price, $sale_price, $stock, $sku;
    public $isFormOpen = false;
    public $selectedId;
	public $unit_id;
	public $selectedParentId; // اضافه شده
    public $children = []; // اضافه شده

    protected $rules = [
        'name' => 'required|string|max:255',
		'unit_id' => 'required|exists:units,id', // 
        'category_id' => 'required|exists:categories,id',
        'purchase_price' => 'required|numeric|min:0',
        'sale_price' => 'required|numeric|min:0',
        'stock' => 'required|integer',
    ];

    public function updatedCategoryId($value)
    {
        // تولید کد موقت پیش‌نمایش SKU محض اطلاع کاربر در لحظه انتخاب دسته
        if ($value) {
            $this->sku = Product::generateSku($value);
        }
    }

    public function render()
    {
		$products = Product::with('category.parent','unit')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $categories = Category::with('children')->whereNull('parent_id')->get();
		

        return view('livewire.product-manager', [
            'products' => $products,
            'categories' => $categories,
			'units' => \App\Models\Unit::all(),
			'children' => $this->children
        ])->layout('layouts.app');
		
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'category_id' => $this->category_id,
			'unit_id' => $this->unit_id, // اضافه کردن واحد
            'purchase_price' => $this->purchase_price,
            'sale_price' => $this->sale_price,
            'stock' => $this->stock,
            'sku' => Product::generateSku($this->category_id),
        ];

        Product::create($data);

        $this->reset(['name', 'category_id', 'purchase_price', 'sale_price', 'stock', 'sku', 'unit_id', 'isFormOpen']);
        session()->flash('status', 'محصول جدید با موفقیت به انبار اضافه شد.');
    }
	public function formatPrice($field)
{
    $this->$field = str_replace(',', '', $this->$field);
}
public function updatedSelectedParentId($value)
{
	
	
	if ($value) {
        $this->children = Category::find($value)->children;
    } else {
        $this->children = [];
    }
}


}