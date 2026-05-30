@extends('layouts.print')

@section('title', 'فاکتور فروش - ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto bg-white print:max-w-full p-6 md:p-10">
    
    {{-- هدر دو ستونه --}}
    <div class="flex flex-wrap justify-between items-start border-b-2 border-gray-200 pb-6 mb-8">
        <div class="text-right">
            @if($settings['show_logo'] && $settings['shop_logo'])
                <img src="{{ Storage::url($settings['shop_logo']) }}" class="h-16 mb-3 object-contain mx-auto md:mx-0">
            @endif
            <h1 class="text-2xl font-black text-gray-800">{{ $settings['shop_name'] }}</h1>
            <div class="text-sm text-gray-600 mt-2 space-y-1">
                <div><i class="fas fa-location-dot w-5 text-amber-500"></i> {{ $settings['shop_address'] }}</div>
                <div><i class="fas fa-phone-alt w-5 text-amber-500"></i> {{ $settings['shop_phone'] }}</div>
                @if($settings['shop_register_number'] ?? '')
                    <div><i class="fas fa-building w-5 text-amber-500"></i> ثبت: {{ $settings['shop_register_number'] }}</div>
                @endif
                @if($settings['shop_economic_code'] ?? '')
                    <div><i class="fas fa-chart-line w-5 text-amber-500"></i> کد اقتصادی: {{ $settings['shop_economic_code'] }}</div>
                @endif
            </div>
        </div>
        <div class="text-left mt-4 md:mt-0">
            <div class="bg-amber-50 px-5 py-3 rounded-xl shadow-sm border border-amber-200">
                <div class="text-xs text-gray-500 uppercase tracking-wide">شماره فاکتور</div>
                <div class="text-xl font-mono font-bold text-amber-700">{{ $invoice->invoice_number }}</div>
            </div>
            <div class="mt-2 text-sm text-gray-500 bg-white px-3 py-1 rounded-lg inline-block">
                <i class="fas fa-calendar-alt ml-1"></i> تاریخ: {{ jdate($invoice->created_at)->format('Y/m/d') }}
            </div>
        </div>
    </div>

    {{-- اطلاعات مشتری و تحویل --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
            <div class="flex items-center gap-2 text-indigo-700 mb-3">
                <i class="fas fa-user-tie text-xl"></i>
                <span class="font-bold text-sm">خریدار</span>
            </div>
            <div class="font-bold text-gray-800 text-lg">{{ $invoice->customer->name ?? '---' }}</div>
            @if($invoice->customer->mobile)
                <div class="text-sm text-gray-600 mt-1"><i class="fas fa-mobile-alt w-5"></i> {{ $invoice->customer->mobile }}</div>
            @endif
            @if($invoice->address)
                <div class="text-sm text-gray-600 mt-1"><i class="fas fa-map-marker-alt w-5"></i> {{ $invoice->address }}</div>
            @endif
        </div>
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
            <div class="flex items-center gap-2 text-indigo-700 mb-3">
                <i class="fas fa-truck text-xl"></i>
                <span class="font-bold text-sm">تحویل گیرنده</span>
            </div>
            <div class="font-medium text-gray-700">{{ $invoice->receiver_name ?? 'همان خریدار' }}</div>
            <div class="text-sm text-gray-500 mt-1">
                روش تحویل: 
                @switch($invoice->delivery_method)
                    @case('internal')
                        فروش داخلی
                        @break
                    @case('shipping')
                        ارسال
                        @break
                    @case('pickup')
                        حضوری
                        @break
                    @default
                        نامشخص
                @endswitch
            </div>
        </div>
    </div>

    {{-- جدول کالاها --}}
    <div class="overflow-x-auto mb-10">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-3 px-3 text-center">ردیف</th>
                    <th class="py-3 px-3 text-center">کد کالا</th>
                    <th class="py-3 px-3 text-right">نام کالا</th>
                    <th class="py-3 px-3 text-center">تعداد</th>
                    <th class="py-3 px-3 text-center">واحد</th>
                    <th class="py-3 px-3 text-left">قیمت واحد</th>
                    <th class="py-3 px-3 text-left">تخفیف</th>
                    <th class="py-3 px-3 text-left">مالیات</th>
                    <th class="py-3 px-3 text-left">جمع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-2.5 px-3 text-center">{{ $index + 1 }}</td>
                    <td class="py-2.5 px-3 text-center font-mono text-xs">{{ $item->product->sku ?? '---' }}</td>
                    <td class="py-2.5 px-3">{{ $item->product->name ?? '---' }}</td>
                    <td class="py-2.5 px-3 text-center">{{ number_format($item->quantity) }}</td>
                    <td class="py-2.5 px-3 text-center">{{ $item->unit_name ?? $item->product->unit->title ?? 'عدد' }}</td>
                    <td class="py-2.5 px-3 text-left">{{ format_currency($item->price) }}</td>
                    <td class="py-2.5 px-3 text-left text-red-600">{{ format_currency($item->discount_amount ?? 0) }}</td>
                    <td class="py-2.5 px-3 text-left">{{ format_currency($item->tax_amount ?? 0) }}</td>
                    <td class="py-2.5 px-3 text-left font-bold">{{ format_currency($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- جمع‌بندی مالی --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="space-y-3 bg-gray-50 rounded-xl p-5">
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span>جمع کل اقلام</span>
                <span>{{ format_currency($invoice->total_amount) }}</span>
            </div>
            @if($invoice->discount_total > 0)
            <div class="flex justify-between text-red-600 border-b border-gray-200 pb-2">
                <span>تخفیف کلی</span>
                <span>- {{ format_currency($invoice->discount_total) }}</span>
            </div>
            @endif
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span>مالیات ({{ $invoice->tax_rate }}%)</span>
                <span>{{ format_currency($invoice->tax_amount) }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
                <span>هزینه ارسال</span>
                <span>{{ format_currency($invoice->delivery_cost ?? 0) }}</span>
            </div>
            <div class="flex justify-between">
                <span>افزایشات (بسته‌بندی، کارمند)</span>
                <span>{{ format_currency($invoice->extra_charges_total ?? 0) }}</span>
            </div>
        </div>

        <div class="bg-amber-50 rounded-xl p-5 border border-amber-200 shadow-sm">
            <div class="flex justify-between text-lg font-bold border-b border-amber-200 pb-2 mb-2">
                <span>مبلغ نهایی (پرداختنی)</span>
                <span class="text-emerald-700">{{ format_currency($invoice->final_amount) }}</span>
            </div>
            @if($invoice->paid_amount > 0)
            <div class="flex justify-between text-sm mt-2">
                <span>پرداخت شده:</span>
                <span>{{ format_currency($invoice->paid_amount) }}</span>
            </div>
            <div class="flex justify-between text-sm text-red-600">
                <span>باقی‌مانده:</span>
                <span>{{ format_currency($invoice->final_amount - $invoice->paid_amount) }}</span>
            </div>
            @endif
            <div class="mt-3 text-xs text-gray-500">
                @if($invoice->payment_type == 'credit')
                    نسیه - سررسید: {{ $invoice->due_date ? jdate($invoice->due_date)->format('Y/m/d') : '-' }}
                @elseif($invoice->payment_type == 'check')
                    چک شماره {{ $invoice->check_number }} - تاریخ {{ $invoice->check_due_date ? jdate($invoice->check_due_date)->format('Y/m/d') : '-' }}
                @else
                    پرداخت نقدی
                @endif
            </div>
        </div>
    </div>

    {{-- پانویس --}}
    <div class="border-t border-gray-200 pt-6 text-center text-sm text-gray-500">
        <p class="italic">{{ $settings['footer_text'] }}</p>
        <div class="flex justify-between mt-6 px-2">
            @if($settings['show_stamp'])
            <div class="w-1/3 text-right border-t border-dashed border-gray-300 pt-2">مهر و امضاء فروشنده</div>
            @endif
            <div class="w-1/3 text-left border-t border-dashed border-gray-300 pt-2">مهر و امضاء خریدار</div>
        </div>
    </div>
</div>
@endsection