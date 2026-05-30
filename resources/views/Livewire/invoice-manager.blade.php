<div class="py-6" dir="rtl" x-data="{ tab: 'info' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 pb-4 border-b">
                <div class="flex gap-6 items-end">
                    <div>
                        <span class="text-xs text-gray-500">شماره فاکتور</span>
                        <div class="text-xl font-mono font-bold text-blue-600">{{ $invoice_number }}</div>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">تاریخ</span>
                        <div class="text-base font-bold">{{ jdate(now())->format('Y/m/d') }}</div>
                    </div>
                </div>
                <h1 class="text-2xl font-black text-gray-900">ثبت فاکتور فروش</h1>
                <a href="{{ route('invoices.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-600">← بازگشت</a>
            </div>

            @if(session()->has('message'))
                <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4">{{ session('message') }}</div>
            @endif
            @if(session()->has('error'))
                <div class="bg-red-100 text-red-800 p-3 rounded-lg mb-4">{{ session('error') }}</div>
            @endif

            {{-- تب‌ها --}}
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex gap-4">
                    <button type="button" @click="tab='info'" :class="tab=='info' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm transition">
                        مشخصات مشتری
                    </button>
                    <button type="button" @click="tab='items'" :class="tab=='items' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm transition">
                        اقلام فاکتور
                    </button>
                    <button type="button" @click="tab='payment'" :class="tab=='payment' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'" class="py-2 px-4 border-b-2 font-medium text-sm transition">
                        جمع‌بندی و پرداخت
                    </button>
                </nav>
            </div>

            <form wire:submit.prevent="saveInvoice">
                {{-- تب مشخصات مشتری --}}
                <div x-show="tab=='info'">
                    <div class="bg-gray-50 rounded-xl p-5 space-y-5">
                        {{-- ردیف اول: انتخاب مشتری (تمام عرض) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">انتخاب مشتری <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <select wire:model.live="customer_id" class="flex-1 border-gray-300 rounded-lg py-2.5">
                                    <option value="">انتخاب کنید...</option>
                                    @foreach($customers as $cust)
                                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg text-base font-medium whitespace-nowrap">+ جدید</button>
                            </div>
                            @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- ردیف دوم: آدرس، شماره تماس، موضوع (سه ستون) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">آدرس</label>
                                <input type="text" wire:model="address" wire:key="address-{{ $customer_id }}"
                                       class="w-full border-gray-300 rounded-lg bg-gray-100 py-2.5 cursor-not-allowed" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شماره تماس</label>
                                <input type="text" wire:model="phone" wire:key="phone-{{ $customer_id }}"
                                       class="w-full border-gray-300 rounded-lg bg-gray-100 py-2.5 cursor-not-allowed" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">موضوع</label>
                                <input type="text" wire:model="subject" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                        </div>

                        {{-- ردیف سوم: تحویل گیرنده، نحوه تحویل، فروشنده --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تحویل گیرنده</label>
                                <input type="text" wire:model="receiver_name" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نحوه تحویل</label>
                                <select wire:model="delivery_method" class="w-full border-gray-300 rounded-lg py-2.5">
                                    <option value="internal">فروش داخلی</option>
                                    <option value="shipping">ارسال</option>
                                    <option value="pickup">خرید حضوری</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">فروشنده</label>
                                <input type="text" wire:model="seller_name" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                        </div>

                        {{-- ردیف چهارم: شماره ثبت، شماره سفارش، شماره سریال --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شماره ثبت</label>
                                <input type="text" wire:model="register_number" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شماره سفارش</label>
                                <input type="text" wire:model="order_number" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">شماره سریال</label>
                                <input type="text" wire:model="serial_number" class="w-full border-gray-300 rounded-lg py-2.5">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== تب اقلام فاکتور ========== --}}
                <div x-show="tab=='items'">
                    {{-- جدول اقلام موجود --}}
                    @if(count($items) > 0)
                    <div class="overflow-x-auto border rounded-xl mb-6">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50 text-gray-600 border-b text-[13px]">
                                <tr>
                                    <th class="p-2">ردیف</th>
                                    <th class="p-2">کد کالا</th>
                                    <th class="p-2">نام کالا</th>
                                    <th class="p-2">تعداد</th>
                                    <th class="p-2">واحد</th>
                                    <th class="p-2">قیمت واحد ({{ $currencyUnit }})</th>
                                    <th class="p-2">تخفیف %</th>
                                    <th class="p-2">بسته‌بندی</th>
                                    <th class="p-2">افزایشات</th>
                                    <th class="p-2">موجودی</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                <tr class="hover:bg-blue-50/30 border-b">
                                    <td class="p-2">{{ $index+1 }}</td>
                                    <td class="p-2 text-gray-500">{{ $item['sku'] }}</td>
                                    <td class="p-2">{{ $item['name'] }}</td>
                                    <td class="p-2">{{ number_format($item['quantity']) }}</td>
                                    <td class="p-2">{{ $item['unit_name'] }}</td>
                                    <td class="p-2">{{ format_currency($item['unit_price']) }}</td>
                                    <td class="p-2">{{ $item['discount_percent'] }}%</td>
                                    <td class="p-2">{{ format_currency($item['packing_cost']) }}</td>
                                    <td class="p-2">{{ format_currency($item['extra_cost']) }}</td>
                                    <td class="p-2">{{ number_format($item['stock_at_sale']) }}</td>
                                    <td class="p-2"><button type="button" wire:click="openDeleteModal({{ $index }})" class="text-red-600">حذف</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- فرم افزودن کالا --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="text-md font-bold mb-4">افزودن کالا به فاکتور</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام کالا</label>
                                <select wire:model.live="selected_product_id" class="w-full border-gray-300 rounded-lg">
                                    <option value="">انتخاب کنید...</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }} ({{ format_currency($prod->sale_price) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">موجودی انبار</label>
                                <div class="w-full border border-gray-200 bg-gray-50 rounded-lg p-2 text-gray-700">
                                    @if($selected_product)
                                        {{ number_format($selected_product->stock) }} {{ $selected_product->unit->title ?? 'عدد' }}
                                    @else
                                        ——
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تعداد</label>
                                <input type="number" wire:model.live="temp_quantity" min="1" class="w-full border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">قیمت واحد ({{ $currencyUnit }})</label>
                                <input type="number" wire:model.live="temp_unit_price" min="0" step="1000" class="w-full border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تخفیف (%)</label>
                                <input type="number" wire:model="temp_discount_percent" min="0" max="100" class="w-full border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">بسته‌بندی ({{ $currencyUnit }})</label>
                                <input type="number" wire:model="temp_packing_cost" min="0" step="1000" class="w-full border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">افزایشات ({{ $currencyUnit }})</label>
                                <input type="number" wire:model="temp_extra_cost" min="0" step="1000" class="w-full border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">کارمند ({{ $currencyUnit }})</label>
                                <input type="number" wire:model="temp_staff_cost" min="0" step="1000" class="w-full border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="flex justify-start mt-2">
                            <button type="button" wire:click="addItem" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">+ افزودن به فاکتور</button>
                        </div>
                    </div>
                </div>

                {{-- ========== تب جمع‌بندی و پرداخت ========== --}}
                <div x-show="tab=='payment'">
                    {{-- جدول اقلام فاکتور (در تب پرداخت هم نمایش داده شود) --}}
                    @if(count($items) > 0)
                    <div class="overflow-x-auto border rounded-xl mb-6">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50 text-gray-600 border-b text-[13px]">
                                <tr>
                                    <th class="p-2">ردیف</th>
                                    <th class="p-2">کد کالا</th>
                                    <th class="p-2">نام کالا</th>
                                    <th class="p-2">تعداد</th>
                                    <th class="p-2">واحد</th>
									<th class="p-2">قیمت واحد ({{ $currencyUnit }})</th>
                                    <th class="p-2">تخفیف %</th>
                                    <th class="p-2">بسته‌بندی</th>
                                    <th class="p-2">افزایشات</th>
                                    <th class="p-2">موجودی</th>
                                    <th class="p-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                <tr class="hover:bg-blue-50/30 border-b">
                                    <td class="p-2">{{ $index+1 }}</td>
                                    <td class="p-2 text-gray-500">{{ $item['sku'] }}</td>
                                    <td class="p-2">{{ $item['name'] }}</td>
                                    <td class="p-2">{{ number_format($item['quantity']) }}</td>
                                    <td class="p-2">{{ $item['unit_name'] }}</td>
                                    <td class="p-2">{{ format_currency($item['unit_price']) }}</td>
                                    <td class="p-2">{{ $item['discount_percent'] }}%</td>
                                    <td class="p-2">{{ format_currency($item['packing_cost']) }}</td>
                                    <td class="p-2">{{ format_currency($item['extra_cost']) }}</td>
                                    <td class="p-2">{{ number_format($item['stock_at_sale']) }}</td>
                                    <td class="p-2"><button type="button" wire:click="openDeleteModal({{ $index }})" class="text-red-600">حذف</button></td>
                                <tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    {{-- جمع‌بندی و پرداخت --}}
                    <div class="flex flex-wrap lg:flex-nowrap gap-6 mt-8">
                        {{-- خلاصه فاکتور --}}
                        <div class="w-full lg:w-1/2 bg-white rounded-xl shadow-md border p-5">
                            <h3 class="text-lg font-bold border-r-4 border-blue-500 pr-3 mb-4">خلاصه فاکتور</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between"><span class="text-gray-600">💰 جمع کل قبل از تخفیف</span><span class="font-bold">{{ format_currency($subtotal) }}</span></div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">🏷️ تخفیف کلی</span>
                                    <div class="flex items-center gap-2">
                                        <label class="inline-flex items-center"><input type="checkbox" wire:model.live="enable_discount"> <span class="mr-1 text-sm">فعال</span></label>
                                        @if($enable_discount)
                                            <input type="number" wire:model.live="discount_percent" class="w-20 border rounded px-1 py-0.5 text-sm"> %
                                            <span class="font-bold">{{ format_currency($discount_total) }}</span>
                                        @else
                                            <span class="text-gray-400 text-sm">غیرفعال</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">🧾 مالیات</span>
                                    <div class="flex items-center gap-2">
                                        <label class="inline-flex items-center"><input type="checkbox" wire:model.live="enable_tax"> <span class="mr-1 text-sm">فعال</span></label>
                                        @if($enable_tax)
                                            <input type="number" wire:model.live="tax_rate" class="w-20 border rounded px-1 py-0.5 text-sm"> %
                                            <span class="font-bold">{{ format_currency($tax_amount) }}</span>
                                        @else
                                            <span class="text-gray-400 text-sm">غیرفعال</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex justify-between"><span class="text-gray-600">📦 جمع افزایشات</span><span class="font-bold">{{ format_currency($extra_charges_total) }}</span></div>
                                <div class="flex justify-between bg-blue-50 p-3 rounded-lg -mx-3 mt-2"><span class="font-bold">💎 مبلغ نهایی (پرداختنی)</span><span class="text-xl font-extrabold text-green-700">{{ format_currency($final_amount) }}</span></div>
                            </div>
                        </div>

                        {{-- اطلاعات پرداخت --}}
                        <div class="w-full lg:w-1/2 bg-white rounded-xl shadow-md border p-5">
                            <h3 class="text-lg font-bold border-r-4 border-blue-500 pr-3 mb-4">اطلاعات پرداخت</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between"><span class="text-gray-600">💳 بدهی جاری مشتری</span><span class="font-bold {{ $customer_debt > 0 ? 'text-red-600' : '' }}">{{ format_currency($customer_debt) }}</span></div>
                                <div><label class="block text-sm font-medium mb-2">روش پرداخت</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center"><input type="radio" wire:model="payment_type" value="cash" class="ml-1"> <span class="mr-1">نقدی</span></label>
                                        <label class="flex items-center"><input type="radio" wire:model="payment_type" value="credit" class="ml-1"> <span class="mr-1">غیرنقدی (نسیه/چک)</span></label>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 pt-4 border-t">
                                    <button type="button" wire:click="$set('items', [])" class="bg-red-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-red-700 transition">انصراف</button>
                                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold text-lg hover:bg-green-700 transition shadow">ثبت نهایی فاکتور</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- مودال حذف --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-800">حذف کالا از فاکتور</h3>
                <button wire:click="$set('showDeleteModal', false)" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
            </div>
            <div class="p-6 text-center">
                <p class="text-gray-700 mb-6">آیا از حذف این کالا از لیست فاکتور اطمینان دارید؟</p>
                <div class="flex justify-center gap-4">
                    <button type="button" wire:click="$set('showDeleteModal', false)" class="w-full bg-white border border-gray-200 text-gray-500 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition-all">انصراف</button>
                    <button type="button" wire:click="confirmDelete" class="w-full bg-red-600 text-white py-3 rounded-lg font-bold text-sm hover:bg-red-700 transition-all">حذف</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>