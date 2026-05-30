<div x-data="{ openDrawer: @entangle('showModal') }">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-black text-gray-900">طرف حساب‌ها</h1>
        <button wire:click="openModal" class="bg-[#1a56db] hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm transition-all active:scale-95">
            + تعریف شخص جدید
        </button>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-right text-sm">
            <thead class="bg-gray-50/50 border-b border-gray-200 text-gray-500">
                <tr>
                    <th class="p-4 font-bold">کد تفصیلی</th>
                    <th class="p-4 font-bold">نام نمایشی</th>
                    <th class="p-4 font-bold">تلفن همراه</th>
                    <th class="p-4 font-bold">نقش</th>
                    <th class="p-4 font-bold text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($parties as $party)
                <tr class="hover:bg-blue-50/20 transition-colors group">
                    <td class="p-4 font-mono text-gray-400">{{ $party->code }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $party->name }}</td>
                    <td class="p-4 text-gray-500 font-mono" dir="ltr">{{ $party->mobile ?? '-' }}</td>
                    <td class="p-4">
                        @if($party->is_customer) <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px]">مشتری</span> @endif
                        @if($party->is_supplier) <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] mr-1">تامین‌کننده</span> @endif
                        @if($party->is_employee) <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] mr-1">سهام دار</span> @endif
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center gap-3 transition-opacity">
                            <button wire:click="openModal({{ $party->id }})"  class="text-[20px] text-blue-600">✎</button>   <!-- جایگزینی کد دکمه حذف -->
							<button wire:click="openDeleteModal({{ $party->id }})" class="text-[20px] text-red-600">🗑</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@if($showModal)
	        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800">تعریف شخص جدید</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-red-500 text-2xl">&times;</button>
                </div>

                <form wire:submit.prevent="store" class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
    <label class="text-sm font-medium">نام</label>
    <input type="text" wire:model="first_name" class="w-full mt-1 border-gray-300 rounded-lg">
    @error('first_name')
        <span class="text-red-500 text-[10px]">{{ $message }}</span>
    @enderror
</div>

<div class="col-span-2 md:col-span-1">
    <label class="text-sm font-medium">نام خانوادگی</label>
    <input type="text" wire:model="last_name" class="w-full mt-1 border-gray-300 rounded-lg">
    @error('last_name')
        <span class="text-red-500 text-[10px]">{{ $message }}</span>
    @enderror
</div>

						<div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">کد ملی</label>
                            <input type="text" wire:model="national_code" class="w-full mt-1 border-gray-300 rounded-lg">
							@error('national_code')
        <span class="text-red-500 text-[10px]">{{ $message }}</span>
    @enderror
                        </div>
						<div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">موبایل</label>
                            <input type="text" wire:model="mobile" class="w-full mt-1 border-gray-300 rounded-lg">
							@error('mobile')
        <span class="text-red-500 text-[10px]">{{ $message }}</span>
    @enderror
                        </div>
						<div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">نام نمایشی</label>
                            <input type="text" wire:model="name" class="w-full mt-1 border-gray-300 rounded-lg">
							@error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

						<div class="col-span-2 md:col-span-1">
                            <label class="text-sm font-medium">آدرس</label>
                            <input type="text" wire:model="address" class="w-full mt-1 border-gray-300 rounded-lg">
						</div>

						<div class="pt-4 border-t border-gray-50">
    <label class="text-[11px] font-bold text-gray-400 uppercase block mb-3">نوع طرف حساب</label>
    <div class="grid grid-cols-3 gap-3">
    <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
        <input type="radio" wire:model="type" value="legal" class="rounded text-blue-600 w-4 h-4">
        <span class="text-xs font-bold text-gray-700">حقوقی</span>
    </label>
    <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
        <input type="radio" wire:model="type" value="real" class="rounded text-blue-600 w-4 h-4">
        <span class="text-xs font-bold text-gray-700">حقیقی</span>
    </label>
</div>
</div>



                       <div class="pt-4 border-t border-gray-50">
    <label class="text-[11px] font-bold text-gray-400 uppercase block mb-3">نقش شخص در سیستم</label>
    <div class="grid grid-cols-3 gap-3">
        <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
            <input type="checkbox" wire:model="is_customer" class="rounded text-blue-600 w-4 h-4">
            <span class="text-xs font-bold text-gray-700">مشتری</span>
        </label>
        <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
            <input type="checkbox" wire:model="is_supplier" class="rounded text-blue-600 w-4 h-4">
            <span class="text-xs font-bold text-gray-700">تامین‌کننده</span>
        </label>
        <label class="flex items-center gap-2 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
            <input type="checkbox" wire:model="is_employee" class="rounded text-blue-600 w-4 h-4">
            <span class="text-xs font-bold text-gray-700">سهام دار</span>
        </label>
    </div>
</div>
                   <div class="grid grid-cols-3 gap-3 flex flex-col gap-3 sticky bottom-0 bg-white shadow-[0_-10px_10px_-5px_rgba(255,255,255,1)]">
                        <button type="submit" class="w-full bg-[#1a56db] text-white py-3 rounded-lg font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-50">
                            تایید و ذخیره
                        </button>
                        <button type="button" wire:click	="$set('showModal', false)" class="w-full bg-white border border-gray-200 text-gray-500 py-3 rounded-lg font-bold text-sm hover:bg-gray-50 transition-all">
                            انصراف
                        </button>
                    </div>
                </form>
            </div>
            </div>
            </div>
    @endif
	
	@if($showDeleteModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">حذف طرف حساب</h3>
            <button 
                wire:click="$set('showDeleteModal', false)" 
                class="text-gray-400 hover:text-red-500 text-2xl"
            >
                &times;
            </button>
        </div>
        <div class="p-6 text-center">
            <p class="text-gray-700 mb-6">آیا از حذف این طرف حساب اطمینان دارید؟</p>
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
