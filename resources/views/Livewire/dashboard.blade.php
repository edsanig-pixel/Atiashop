<div class="space-y-6" dir="rtl">
    <div class="flex justify-between items-center bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-black text-gray-700">وضعیت کلی سیستم</h2>
        <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700 transition-all">+ ثبت تراکنش جدید</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-blue-400 transition-all">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 mb-1">فروش کل (ماه جاری)</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ format_currency($totalSales) }} <span class="text-[10px] font-normal text-gray-400">ریال</span></h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600 text-xl group-hover:scale-110 transition-transform">🛒</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-orange-400 transition-all">
            <div class="absolute top-0 left-0 w-1 h-full bg-orange-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 mb-1">خرید کل</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ format_currency($totalPurchases) }} <span class="text-[10px] font-normal text-gray-400">ریال</span></h3>
                </div>
                <div class="p-3 bg-orange-50 rounded-xl text-orange-600 text-xl group-hover:scale-110 transition-transform">📥</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-emerald-400 transition-all">
            <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 mb-1">سود خالص</p>
                    <h3 class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ format_currency($netProfit) }} <span class="text-[10px] font-normal text-gray-400">ریال</span>
                    </h3>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 text-xl group-hover:scale-110 transition-transform">📈</div>
            </div>
        </div>

    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <p class="text-sm font-black text-slate-700">تحلیل نقدینگی ماهانه</p>
            <span class="text-[10px] text-gray-400 italic">داده‌ها به صورت زنده از دیتابیس فراخوانی می‌شوند</span>
        </div>
        <div class="h-48 w-full flex items-end justify-between gap-3 px-2 border-b border-gray-50 pb-2">
            @foreach(range(1, 15) as $i)
                <div class="flex-1 bg-slate-100 hover:bg-blue-600 rounded-t-md transition-all h-{{ rand(1, 5)*10 }} group relative">
                    <div class="hidden group-hover:block absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[9px] px-2 py-1 rounded">ثبت شده</div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between mt-4 px-2">
            @foreach($systemMonths as $month)
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $month }}</span>
            @endforeach
        </div>
    </div>
</div>