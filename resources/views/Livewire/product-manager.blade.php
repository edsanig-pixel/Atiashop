<div class="py-6" dir="rtl">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div class="relative w-full md:w-1/3">
                    <input type="text" wire:model.live="search" placeholder="جستجوی کالا یا کد SKU..." 
                           class="w-full pr-10 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                </div>
                
                <button wire:click="$set('isFormOpen', true)" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition w-full md:w-auto">
                    + تعریف کالای جدید
                </button>
            </div>

            <div class="overflow-x-auto border rounded-xl">
                <table class="w-full text-right text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b text-[13px]">
                        <tr>
                            <th class="p-4">کد شناسایی (SKU)</th>
                            <th class="p-4">نام کالا</th>
                            <th class="p-4">دسته‌بندی</th>
                            <th class="p-4 text-center">موجودی</th>
                            <th class="p-4">قیمت فروش (ریال)</th>
                            <th class="p-4">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700">
                        @foreach($products as $product)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="p-4 font-mono font-bold text-blue-600">{{ $product->sku }}</td>
                                <td class="p-4 font-bold">{{ $product->name }}</td>
                                <td class="p-4 text-xs">
                                    <span class="text-gray-400">{{ $product->category->parent->name }}</span> / {{ $product->category->name }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 rounded-full {{ $product->stock < 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $product->stock }}  {{ $product->unit->title ?? 'بدون واحد' }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-slate-900">{{ format_currency($product->sale_price) }}</td>
                                <td class="p-4 text-center">
                                    <button class="text-gray-400 hover:text-blue-600">📝</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $products->links() }}</div>
        </div>
    </div>

    @if($isFormOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800">تعریف کالای جدید</h3>
                    <button wire:click="$set('isFormOpen', false)" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                </div>
                
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">نام کالا</label>
                            <input type="text" wire:model="name" class="w-full mt-1 border-gray-300 rounded-lg">
                        </div>
						<div class="col-span-2 md:col-span-1">
    <label class="block text-sm font-medium text-gray-700">واحد محصول</label>
    <select name="unit_id" wire:model="unit_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        <option value="">انتخاب واحد</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}">{{ $unit->title }} ({{ $unit->symbol }})</option>
        @endforeach
    </select>
    @error('unit_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
						<div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">دسته‌بندی (فقط زیرمجموعه)</label>
                            <select wire:model.live="category_id" class="w-full mt-1 border-gray-300 rounded-lg">
                                <option value="">انتخاب کنید...</option>
                                @foreach($categories as $parent)
                                    <optgroup label="{{ $parent->name }}">
                                        @foreach($parent->children as $child)
                                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 bg-blue-50 p-4 rounded-xl">
                        <div>
                            <label class="text-xs font-bold text-blue-700">قیمت خرید</label>
                            <input type="number" wire:model="purchase_price" class="w-full mt-1 border-blue-200 rounded-lg">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-green-700">قیمت فروش</label>
                            <input type="number" wire:model="sale_price" class="w-full mt-1 border-green-200 rounded-lg">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-amber-700">موجودی اولیه</label>
                            <input type="number" wire:model="stock" class="w-full mt-1 border-amber-200 rounded-lg">
                        </div>
                    </div>
                    @if($sku)
                        <div class="bg-gray-100 p-2 rounded text-center font-mono text-sm">
                            کد کالا اختصاصی: <span class="text-blue-600 font-bold">{{ $sku }}</span>
                        </div>
                    @endif
                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <button type="button" wire:click="$set('isFormOpen', false)" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">انصراف</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">ثبت در انبار</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>  