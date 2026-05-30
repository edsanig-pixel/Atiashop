<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مدیریت دسته‌بندی‌ها و ساختار انبار') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-indigo-500">
                        <h3 class="text-md font-bold text-gray-700 mb-4 flex items-center">
                            <svg class="w-5 h-5 ml-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            ایجاد دسته‌بندی جدید
                        </h3>

                        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="name" value="نام فارسی" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-50" placeholder="مثلا: قطعات جانبی" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="parent_id" value="سطح دسته‌بندی" />
                                <select name="parent_id" id="parent_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50">
                                    <option value="">دسته اصلی (والد)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="w-full justify-center py-3">
                                    {{ __('ثبت در سیستم') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-md font-bold text-gray-700">ساختار درختی انبار</h3>
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">تعداد کل: {{ $categories->count() }} دسته اصلی</span>
                        </div>

                        @if(session('status') === 'category-deleted')
                            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">دسته بندی با موفقیت حذف شد.</div>
                        @endif
                         @if(session('error'))
                            <div class="mb-4 p-3 bg-amber-100 text-amber-700 rounded-lg text-sm">{{ session('error') }}</div>
                        @endif

                        <div class="space-y-4">
                            @forelse($categories as $category)
                                <div class="border rounded-xl p-4 hover:border-indigo-300 transition-all shadow-sm">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 font-bold ml-3">
                                                {{ $category->code }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800">{{ $category->name }}</h4>
                                                <span class="text-[10px] text-gray-400">شناسه: #{{ $category->id }}</span>
                                            </div>
                                        </div>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('آیا از حذف این دسته اطمینان دارید؟');">
                                            @csrf @method('DELETE')
                                            <button class="text-gray-400 hover:text-red-500 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>

                                    @if($category->children->count() > 0)
                                        <div class="mt-4 mr-10 space-y-2 border-r-2 border-gray-100 pr-4">
                                            @foreach($category->children as $child)
                                                <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg group">
                                                    <span class="text-sm text-gray-600">
                                                        <span class="font-mono text-indigo-400 ml-2">{{ $child->code }}</span>
                                                        {{ $child->name }}
                                                    </span>
                                                    <form action="{{ route('categories.destroy', $child) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="opacity-0 group-hover:opacity-100 text-red-300 hover:text-red-500 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-400">هنوز هیچ دسته‌ای ثبت نشده است.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>