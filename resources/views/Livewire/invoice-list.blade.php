<div class="py-6" dir="rtl">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-black text-gray-900">لیست فاکتورها</h1>
                <a href="{{ route('invoices.create') }}" 
                   class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition w-full md:w-auto text-center">
                    + ثبت فاکتور جدید
                </a>
            </div>

            @if(session()->has('message'))
                <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4">{{ session('message') }}</div>
            @endif

            <div class="overflow-x-auto border rounded-xl">
                <table class="w-full text-right text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b text-[13px]">
                        <tr>
                            <th class="p-4">شماره فاکتور</th>
                            <th class="p-4">مشتری</th>
                            <th class="p-4">مبلغ کل</th>
                            <th class="p-4">تاریخ</th>
                            <th class="p-4">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="p-4 font-mono font-bold text-blue-600">{{ $invoice->invoice_number }}</td>
                                <td class="p-4 font-bold">{{ $invoice->customer->name ?? 'نامشخص' }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ format_currency($invoice->total_amount) }}</td>
                                <td class="p-4">{{ jdate($invoice->created_at)->format('Y/m/d') }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-800">📄 جزئیات</a>
                                        <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="text-gray-500 hover:text-blue-600"><i class="fas fa-print"></i></a>
                                        <button wire:click="delete({{ $invoice->id }})" 
                                                onclick="return confirm('آیا از حذف این فاکتور اطمینان دارید؟')" 
                                                class="text-red-600 hover:text-red-800">🗑 حذف</button>
                                    </div>
                                </td>
                             </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $invoices->links() }}</div>
        </div>
    </div>
</div>