<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ویرایش محصول: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6">
            <div class="bg-white shadow-sm rounded-lg p-6">

                <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- نام محصول --}}
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">نام محصول</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- دسته‌بندی کالا --}}
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">دسته‌بندی</label>
                            <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                @foreach($categories as $category)
                                    @if(is_null($category->parent_id))
                                        <optgroup label="{{ $category->name }}">
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                                    {{ $child->name }} (کد: {{ $child->code }})
                                                </option>
                                                
                                                @foreach($child->children as $grandChild)
                                                    <option value="{{ $grandChild->id }}" {{ old('category_id', $product->category_id) == $grandChild->id ? 'selected' : '' }}>
                                                        &nbsp;&nbsp; -- {{ $grandChild->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- کد شناسایی کالا (SKU) --}}
                    <div>
                        <label class="block mb-1 font-medium text-gray-700">کد شناسایی (SKU) - <span class="text-red-500 text-xs">غیرقابل تغییر به صورت دستی</span></label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" readonly
                               class="w-full border-gray-200 bg-gray-100 rounded-md shadow-sm cursor-not-allowed font-mono">
                        <p class="text-xs text-gray-400 mt-1">تغییر SKU باعث اختلال در سیستم انبارداری می‌شود.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- قیمت خرید --}}
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">قیمت خرید</label>
                            <input type="number" step="0.01" name="purchase_price"
                                   value="{{ old('purchase_price', $product->purchase_price) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        {{-- قیمت فروش --}}
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">قیمت فروش</label>
                            <input type="number" step="0.01" name="sale_price"
                                   value="{{ old('sale_price', $product->sale_price) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        {{-- موجودی --}}
                        <div>
                            <label class="block mb-1 font-medium text-gray-700">موجودی فعلی</label>
                            <input type="number" name="stock"
                                   value="{{ old('stock', $product->stock) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-between pt-4 border-t">
                        <a href="{{ route('products.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                            بازگشت
                        </a>

                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow-md transition">
                            بروزرسانی محصول
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>