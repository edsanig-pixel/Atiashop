<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // لود کردن دسته‌ها به همراه فرزندان برای جلوگیری از کوئری اضافه (Eager Loading)
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
    ]);

    // پیدا کردن آخرین کد ثبت شده در این سطح
    $parentId = $request->parent_id;
    $lastCategory = \App\Models\Category::where('parent_id', $parentId)
        ->orderBy('code', 'desc')
        ->first();

    if (!$lastCategory) {
        $newCode = '01'; // اگر اولین دسته در این سطح است
    } else {
        $newCode = str_pad(intval($lastCategory->code) + 1, 2, '0', STR_PAD_LEFT);
    }

    // چک کردن سقف ۹۹
    if (intval($newCode) > 99) {
        return back()->with('error', 'سقف تعریف دسته‌بندی در این سطح (۹۹ مورد) پر شده است.');
    }

    \App\Models\Category::create([
        'name'      => $request->name,
        'parent_id' => $parentId,
        'code'      => $newCode,
    ]);

    return redirect()->route('categories.index')->with('status', 'category-created');
}

    public function destroy(Category $category)
    {
        // جلوگیری از حذف اگر زیرمجموعه دارد (برای امنیت دیتا)
        if ($category->children()->count() > 0) {
            return back()->with('error', 'این دسته دارای زیرمجموعه است و قابل حذف نیست.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('status', 'category-deleted');
    }
}