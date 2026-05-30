<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پنل مدیریت {{ config('app.name', 'Laravel') }}</title>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-[#F9FAFC] text-[#1F2937]" dir="rtl">
    <div x-data="{ sidebarOpen: true, profileOpen: false }" class="flex h-screen overflow-hidden">
        {{-- سایدبار (منوی کناری) --}}
        <aside x-show="sidebarOpen" 
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="transform -translate-x-full"
               x-transition:enter-end="transform translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="transform translate-x-0"
               x-transition:leave-end="transform -translate-x-full"
               class="sidebar w-64 bg-white shadow-sm flex-shrink-0 flex flex-col z-40 border-l border-gray-100">
            
            {{-- لوگو (فقط آیکون) --}}
            <div class="h-16 flex items-center justify-center border-b border-gray-100">
                <i class="fas fa-chart-line text-2xl text-[#0EA5E9]"> {{ config('app.name', 'Laravel') }} </i>
            </div>

            {{-- منو (Livewire) --}}
            <div class="flex-1 overflow-y-auto py-4">
                @livewire('layouts.sidebar')
            </div>

            {{-- بخش پروفایل در پایین سایدبار --}}
            <div class="border-t border-gray-100 p-4 relative">
                <div @click="profileOpen = !profileOpen" 
                     class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 rounded-lg p-2 transition">
                    <div class="w-9 h-9 rounded-full bg-[#0EA5E9] flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'کاربر' }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                    </div>
                </div>

                {{-- منوی آبشاری که به سمت بالا باز می‌شود --}}
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-2"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-100 z-50">
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-user w-4 text-gray-400"></i> پروفایل
                        </a>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-lock w-4 text-gray-400"></i> تنظیمات حساب
                        </a>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chart-line w-4 text-gray-400"></i> گزارشات
                        </a>
                        @can('manage users')
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-shield-alt w-4 text-gray-400"></i> پنل مدیریت
                        </a>
                        @endcan
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-graduation-cap w-4 text-gray-400"></i> آموزش
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                <i class="fas fa-sign-out-alt w-4"></i> خروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- محتوای اصلی (نوار ابزار بالا + هدر + محتوا) --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.navigation', ['sidebarOpen' => 'sidebarOpen'])

            <main class="flex-1 overflow-y-auto p-6">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>