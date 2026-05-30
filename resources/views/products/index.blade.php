<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            مدیریت محصولات
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-bold">لیست محصولات</h4>
                <a href="{{ route('products.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                   @can('create products')
                    افزودن محصول
                    @endcan
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right">ردیف</th>
                                <th class="px-4 py-2 text-right">تاریخ</th>
                                <th class="px-4 py-2 text-right">نام</th>
                                <th class="px-4 py-2 text-right">دسته‌بندی</th> {{-- ستون اضافه شده برای وضوح --}}
                                <th class="px-4 py-2 text-right">کد شناسایی کالا</th>
                                <th class="px-4 py-2 text-right">خرید</th>
                                <th class="px-4 py-2 text-right">فروش</th>
                                <th class="px-4 py-2 text-right">موجودی</th>
                                <th class="px-4 py-2 text-center">عملیات</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($products as $product)
                            <tr>
                                <td class="px-4 py-2">{{ $product->id }}</td>
                                <td class="px-4 py-2">{{ $product->created_at_jalali }}</td>
                                <td class="px-4 py-2">{{ $product->name }}</td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{-- نمایش سلسله‌مراتبی دسته --}}
                                    @if($product->category->parent)
                                        <small>{{ $product->category->parent->name }} ></small>
                                    @endif
                                    {{ $product->category->name }}
                                </td>
                                <td class="px-4 py-2 font-mono font-bold text-blue-600">
                                    {{ $product->sku }}
                                </td>
                                <td class="px-4 py-2">{{ number_format($product->purchase_price) }}</td>
                                <td class="px-4 py-2">{{ number_format($product->sale_price) }}</td>

                                <td class="px-4 py-2">
                                    @if($product->stock < 5)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-center space-x-2 space-x-reverse">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="px-3 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                        ویرایش
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('حذف شود؟')"
                                                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-gray-500">هیچ محصولی ثبت نشده</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>