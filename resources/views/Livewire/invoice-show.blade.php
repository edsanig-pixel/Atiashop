<div class="py-6" dir="rtl">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-gray-900">جزئیات فاکتور</h2>
                <a href="{{ route('invoices.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-600 transition">
                    ← بازگشت به لیست
                </a>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">شماره فاکتور</p>
                        <p class="font-mono font-bold text-blue-600 text-lg">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">مشتری</p>
                        <p class="font-bold text-gray-800">{{ $invoice->customer->name ?? 'نامشخص' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">تاریخ ثبت</p>
                        <p class="text-gray-700">{{ jdate($invoice->created_at)->format('Y/m/d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">مبلغ کل</p>
                        <p class="text-xl font-bold text-green-600">{{ format_currency($invoice->total_amount) }}</p>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-4">محصولات خریداری شده</h3>
            <div class="overflow-x-auto border rounded-xl">
                <table class="w-full text-right text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b text-[13px]">
                        <tr>
                            <th class="p-4">نام محصول</th>
                            <th class="p-4">تعداد</th>
                            <th class="p-4">قیمت واحد</th>
                            <th class="p-4">جمع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700">
                        @foreach($invoice->items as $item)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="p-4 font-bold">{{ $item->product->name ?? '---' }}</td>
                                <td class="p-4">{{ number_format($item->quantity) }}<td>
                                <td class="p-4">{{ format_currency($item->price) }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ format_currency($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>