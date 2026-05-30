<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            افزودن محصول جدید
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- دسته بندی اصلاح شده --}}
                    <div>
                        <label class="block mb-1 font-medium">دسته‌بندی (انتخاب زیرمجموعه اجباری است)</label>
                        <select name="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($categories as $category)
                                @if(is_null($category->parent_id))
                                    {{-- دسته اصلی فقط به عنوان عنوان نمایش داده می‌شود --}}
                                    <optgroup label="{{ $category->name }} (کد: {{ $category->code }})">
                                        @foreach($category->children as $child)
                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }} (کد: {{ $child->code }})
                                            </option>
                                            
                                            @foreach($child->children as $grandChild)
                                                <option value="{{ $grandChild->id }}" {{ old('category_id') == $grandChild->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp; -- {{ $grandChild->name }} (کد: {{ $grandChild->code }})
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- نام محصول --}}
                    <div>
                        <label class="block mb-1 font-medium">نام محصول</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- قیمت خرید --}}
                    <div>
                        <label class="block mb-1 font-medium">قیمت خرید</label>
                        <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        @error('purchase_price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- قیمت فروش --}}
                    <div>
                        <label class="block mb-1 font-medium">قیمت فروش</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price') }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        @error('sale_price')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- موجودی --}}
                    <div>
                        <label class="block mb-1 font-medium">موجودی اولیه</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200">
                        @error('stock')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- دکمه‌ها (بدون تغییر در عملکرد) --}}
                    <div class="flex justify-between pt-4">
                        <a href="{{ route('products.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                            بازگشت
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            ذخیره محصول
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>