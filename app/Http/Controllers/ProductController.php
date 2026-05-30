<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view products', only: ['index']),
            new Middleware('permission:create products', only: ['create', 'store']),
            new Middleware('permission:edit products', only: ['edit', 'update']),
            new Middleware('permission:delete products', only: ['destroy']),
        ];
    }

    public function index()
    {
        $products = Product::with('category.parent')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // لود کردن دسته‌ها برای فرم ایجاد
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0'
        ]);

        return DB::transaction(function () use ($validated) {
            $category = Category::with('parent')->lockForUpdate()->findOrFail($validated['category_id']);

            // ۱. منطق تولید SKU بر اساس درخت دسته‌بندی
            if ($category->parent) {
                $mainCode = str_pad($category->parent->code, 2, '0', STR_PAD_LEFT);
                $subCode  = str_pad($category->code, 2, '0', STR_PAD_LEFT);
            } else {
                $mainCode = str_pad($category->code, 2, '0', STR_PAD_LEFT);
                $subCode  = '00';
            }

            $nextId = Product::where('category_id', $category->id)->count() + 1;
            $serial = str_pad($nextId, 5, '0', STR_PAD_LEFT);
            $sku = $mainCode . $subCode . $serial;

            while (Product::where('sku', $sku)->exists()) {
                $nextId++;
                $serial = str_pad($nextId, 5, '0', STR_PAD_LEFT);
                $sku = $mainCode . $subCode . $serial;
            }

            $product = Product::create([
                'category_id'    => $validated['category_id'],
                'name'           => $validated['name'],
                'sku'            => $sku,
                'purchase_price' => $validated['purchase_price'],
                'sale_price'     => $validated['sale_price'],
                'stock'          => $validated['stock'],
            ]);

            // ثبت حرکت انبار اولیه
            if ($product->stock > 0) {
                $product->stockMovements()->create([
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'reference' => 'موجودی اولیه'
                ]);
            }

            return redirect()->route('products.index')->with('success', "محصول با کد $sku ثبت شد.");
        });
    }

    public function edit(Product $product)
    {
        // اصلاح شده: ارسال دسته‌ها به ویو ویرایش
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            // SKU را معمولاً در ویرایش تغییر نمی‌دهیم، اما اگر لازم است:
            'sku'            => 'required|unique:products,sku,' . $product->id,
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت بروزرسانی شد.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'محصول حذف شد');
    }
}