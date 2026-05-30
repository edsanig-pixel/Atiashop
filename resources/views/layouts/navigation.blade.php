{{-- نوار ابزار بالا (Top Navbar) با دکمه همبرگر در سمت راست و آیکون‌های عملیاتی در سمت چپ --}}
<header class="bg-white border-b border-gray-100 sticky top-0 z-30">
    <div class="flex justify-between items-center px-5 h-16">
        {{-- سمت راست: دکمه همبرگر (برای باز/بسته کردن سایدبار) --}}
        <div>
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        {{-- سمت چپ: سه آیکون عملیاتی (پشتیبانی، تنظیمات، حالت روز/شب) --}}
        <div class="flex items-center gap-4 text-gray-500">
            <i class="fas fa-question-circle text-xl cursor-pointer hover:text-[#0EA5E9] transition"></i>
            <i class="fas fa-cog text-xl cursor-pointer hover:text-[#0EA5E9] transition"></i>
            <i class="fas fa-sun text-xl cursor-pointer hover:text-[#0EA5E9] transition"></i>
        </div>
    </div>
</header>