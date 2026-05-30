<section class="p-6">
    <style>
        .ati-table { display: table !important; width: 100% !important; border-collapse: collapse !important; }
        .ati-thead { display: table-header-group !important; }
        .ati-tbody { display: table-row-group !important; }
        .ati-tr { display: table-row !important; }
        .ati-th, .ati-td { display: table-cell !important; vertical-align: middle !important; }
    </style>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-slate-700">مدیریت واحدهای کالا</h2>
        <button type="button" 
                wire:click="$set('showModal', true)" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md shadow-md transition-all font-bold">
            + تعریف واحد جدید
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="ati-table text-right">
            <thead class="ati-thead bg-slate-50 border-b border-gray-200">
                <tr class="ati-tr">
                    <th class="ati-th px-6 py-4 text-sm font-bold text-slate-600 w-20">ردیف</th>
                    <th class="ati-th px-6 py-4 text-sm font-bold text-slate-600">عنوان واحد</th>
                    <th class="ati-th px-6 py-4 text-sm font-bold text-slate-600">نماد</th>
                    <th class="ati-th px-6 py-4 text-sm font-bold text-slate-600 text-left">عملیات</th>
                </tr>
            </thead>
            <tbody class="ati-tbody divide-y divide-gray-100">
                @forelse($units as $index => $unit)
                    <tr class="ati-tr hover:bg-blue-50/30 transition-colors">
                        <td class="ati-td px-6 py-4 text-sm text-slate-500">{{ $index + 1 }}</td>
                        <td class="ati-td px-6 py-4 text-sm font-semibold text-slate-900">{{ $unit->title }}</td>
                        <td class="ati-td px-6 py-4 text-sm">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-100">
                                {{ $unit->symbol ?? '---' }}
                            </span>
                        </td>
                        <td class="ati-td px-6 py-4 text-left">
                            <button wire:click="delete({{ $unit->id }})" 
                                    wire:confirm="آیا از حذف این واحد اطمینان دارید؟" 
                                    class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="ati-tr">
                        <td colspan="4" class="ati-td px-6 py-12 text-center text-slate-400 italic">
                            هیچ واحدی در سیستم تعریف نشده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
                
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg z-50 overflow-hidden">
                    <div class="bg-slate-50 border-b p-4 flex justify-between items-center">
                        <span class="font-bold text-slate-800">ایجاد واحد اندازه‌گیری جدید</span>
                        <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-red-500 text-2xl">&times;</button>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">عنوان واحد (مثلاً: عدد)</label>
                            <input type="text" wire:model="title" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">نماد واحد (مثلاً: pcs)</label>
                            <input type="text" wire:model="symbol" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('symbol') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 flex justify-end gap-3">
                        <button wire:click="$set('showModal', false)" class="bg-white border border-gray-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50">انصراف</button>
                        <button wire:click="save" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-200">ذخیره اطلاعات</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>