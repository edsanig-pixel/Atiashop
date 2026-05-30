<section>
<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">مدیریت واحدهای کالا</h2>
        <button wire:click="$set('showModal', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow font-semibold transition">
            + واحد جدید
        </button>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-right border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">ردیف</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">عنوان</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">نماد</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($units as $index => $unit)
                    <tr wire:key="unit-{{ $unit->id }}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $unit->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $unit->symbol }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                            <button wire:click="openDeleteModal({{ $unit->id }})" class="text-red-600 hover:text-red-900">🗑</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-gray-500 opacity-75" wire:click="$set('showModal', false)"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">تعریف واحد جدید</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700"> عنوان ( مثلا : کیلوگرم)</label>
                            <input type="text" wire:model="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نماد (مثلا : KG)</label>
                            <input type="text" wire:model="symbol" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="save" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">ذخیره</button>
                    <button wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">انصراف</button>
                </div>
            </div>
        </div>
    @endif
	
	@if($showDeleteModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">تایید حذف واحد</h3>
            <button 
                wire:click="$set('showDeleteModal', false)" 
                class="text-gray-400 hover:text-red-500 text-2xl"
            >
                &times;
            </button>
        </div>
        <div class="p-6 text-center">
            <p class="text-gray-700 mb-6">آیا از حذف این واحد اطمینان دارید ؟</p>
            <div class="flex justify-center gap-4">
                <button 
                    type="button" 
                    wire:click="$set('showDeleteModal', false)" 
                    class="w-full bg-white border border-gray-200 text-gray-500 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition-all"
                >
                    انصراف
                </button>
                <button 
                    type="button" 
                    wire:click="confirmDelete" 
                    class="w-full bg-red-600 text-white py-3 rounded-lg font-bold text-sm hover:bg-red-700 transition-all"
                >
                    حذف
                </button>
            </div>
        </div>
    </div>
</div>
@endif

</div>
</section>