<div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-bold mb-6">تنظیمات مرکزی فروشگاه</h2>

        @if(session()->has('message'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('message') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <div x-data="{ tab: 'general' }">
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex gap-4">
                    <button @click="tab='general'" :class="tab=='general' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm">عمومی</button>
                    <button @click="tab='invoice'" :class="tab=='invoice' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm">فاکتور و چاپ</button>
                    <button @click="tab='dashboard'" :class="tab=='dashboard' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm">کارت‌های داشبورد</button>
                    <button @click="tab='backup'" :class="tab=='backup' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm">پشتیبان‌گیری</button>
                </nav>
            </div>

            {{-- تب عمومی --}}
            <div x-show="tab=='general'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium">نام فروشگاه</label><input type="text" wire:model="shop_name" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">واحد پولی</label><input type="text" wire:model="currency" class="w-full border rounded-lg p-2"></div>
                    <div class="md:col-span-2"><label class="block text-sm font-medium">آدرس</label><input type="text" wire:model="shop_address" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">تلفن</label><input type="text" wire:model="shop_phone" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">شماره ثبت</label><input type="text" wire:model="shop_register_number" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">کد اقتصادی</label><input type="text" wire:model="shop_economic_code" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">لوگو (تصویر)</label><input type="file" wire:model="logo" class="w-full border rounded-lg p-2"></div>
                </div>
            </div>

            {{-- تب فاکتور و چاپ --}}
            <div x-show="tab=='invoice'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium">فعال بودن مالیات</label><input type="checkbox" wire:model="tax_enabled" class="rounded"></div>
                    <div><label class="block text-sm font-medium">نرخ مالیات (%)</label><input type="number" step="0.5" wire:model="tax_rate" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">فعال بودن تخفیف در فاکتور</label><input type="checkbox" wire:model="discount_enabled" class="rounded"></div>
                    <div><label class="block text-sm font-medium">متن پاورقی فاکتور</label><input type="text" wire:model="invoice_footer_text" class="w-full border rounded-lg p-2"></div>
                    <div><label class="block text-sm font-medium">اندازه چاپ</label>
                        <select wire:model="invoice_print_size" class="w-full border rounded-lg p-2">
                            <option value="A4">A4</option>
                            <option value="thermal">حرارتی (۸۰mm)</option>
                        </select>
                    </div>
                    <div><label class="block text-sm font-medium">نمایش لوگو در چاپ</label><input type="checkbox" wire:model="invoice_show_logo" class="rounded"></div>
                    <div><label class="block text-sm font-medium">نمایش مهر/امضا در چاپ</label><input type="checkbox" wire:model="invoice_show_stamp" class="rounded"></div>
                </div>
            </div>

            {{-- تب کارت‌های داشبورد --}}
            <div x-show="tab=='dashboard'">
                <p class="mb-2 text-sm text-gray-600">کارت‌هایی که می‌خواهید در داشبورد نمایش داده شوند را انتخاب کنید:</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($cardOptions as $key => $label)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" value="{{ $key }}" wire:model="dashboard_cards" class="rounded">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- تب پشتیبان‌گیری --}}
            <div x-show="tab=='backup'">
                <div class="flex flex-wrap gap-4">
                    <button wire:click="backup" class="bg-blue-600 text-white px-4 py-2 rounded">دریافت پشتیبان (SQL)</button>
                    <div class="flex items-center gap-2">
                        <input type="file" wire:model="restoreFile" class="border rounded p-1">
                        <button wire:click="restore" class="bg-gray-600 text-white px-4 py-2 rounded">بازیابی</button>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-500">توجه: بازیابی کل دیتابیس را بازنویسی می‌کند. حتماً قبلاً پشتیبان بگیرید.</div>
            </div>
        </div>

        <div class="mt-6">
            <button wire:click="save" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold">ذخیره تنظیمات</button>
        </div>
    </div>
</div>