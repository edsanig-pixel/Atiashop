<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">داشبورد مدیریتی</h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6 grid grid-cols-3 gap-6">

            <div class="bg-white p-6 shadow rounded">
                <div>تعداد کاربران</div>
                <div class="text-2xl font-bold">{{ $totalUsers }}</div>
            </div>

            <div class="bg-white p-6 shadow rounded">
                <div>تعداد محصولات</div>
                <div class="text-2xl font-bold">{{ $totalProducts }}</div>
            </div>

            <div class="bg-white p-6 shadow rounded">
                <div>محصولات کم‌موجودی</div>
                <div class="text-2xl font-bold text-red-600">{{ $lowStock }}</div>
            </div>

        </div>
    </div>
</x-app-layout>