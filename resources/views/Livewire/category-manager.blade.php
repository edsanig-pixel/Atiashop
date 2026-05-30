<div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" dir="rtl">
            
            <div class="md:col-span-1">
                <div class="bg-white shadow-sm rounded-xl p-6 border-t-4 border-blue-600">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">تعریف دسته‌بندی</h3>
                    
                    <form wire:submit.prevent="store" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام دسته‌بندی</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">انتخاب والد</label>
                            <select wire:model="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                                <option value="">دسته اصلی (والد)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                            ثبت در سیستم
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-sm rounded-xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">ساختار درختی انبار</h3>
                        @if(session()->has('status'))
                            <span class="text-green-600 text-sm font-bold">{{ session('status') }}</span>
                        @endif
                        @if(session()->has('error'))
                            <span class="text-red-600 text-sm font-bold">{{ session('error') }}</span>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @foreach($categories as $category)
                            <div class="border rounded-lg p-3 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded font-mono font-bold">{{ $category->code }}</span>
                                        <span class="font-bold text-gray-700">{{ $category->name }}</span>
                                    </div>
                                    <button button wire:click="openDeleteModal({{ $category->id }})" class="text-red-400 hover:text-red-600">
                                        🗑
                                    </button>
                                </div>

                                @if($category->children->count() > 0)
                                    <div class="mt-2 mr-6 border-r-2 border-blue-100 pr-4 space-y-2">
                                        @foreach($category->children as $child)
                                            <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                                                <span class="text-sm text-gray-600">
                                                    <span class="text-blue-400 font-mono ml-2">{{ $child->code }}</span>
                                                    {{ $child->name }}
                                                </span>
                                                <button button wire:click="openDeleteModal({{ $child->id }})" class="text-red-300 hover:text-red-500">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
	@if($showDeleteModal)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">تایید حذف دسته بندی</h3>
            <button 
                wire:click="$set('showDeleteModal', false)" 
                class="text-gray-400 hover:text-red-500 text-2xl"
            >
                &times;
            </button>
        </div>
        <div class="p-6 text-center">
            <p class="text-gray-700 mb-6">آیا از حذف این دسته بندی اطمینان دارید؟</p>
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