<div class="py-6" dir="rtl">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-extrabold text-slate-800">مدیریت دسترسی و کاربران</h2>
                <button wire:click="$set('isFormOpen', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-bold shadow-md transition-all">
                    + تعریف کاربر جدید
                </button>
            </div>

            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="جستجوی نام یا ایمیل..." class="w-full md:w-1/3 border-gray-300 rounded-lg focus:ring-indigo-500">
            </div>

            <div class="overflow-hidden border rounded-xl">
                <table class="w-full text-right text-sm">
                    <thead class="bg-slate-50 text-slate-600 border-b">
                        <tr>
                            <th class="p-4">نام و نام خانوادگی</th>
                            <th class="p-4">ایمیل (نام کاربری)</th>
                            <th class="p-4 text-center">تاریخ عضویت</th>
                            <th class="p-4 text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-bold text-slate-700">{{ $user->name }}</td>
                                <td class="p-4 text-slate-500">{{ $user->email }}</td>
                                <td class="p-4 text-center text-xs text-gray-400" dir="ltr">
                                    {{ $user->created_at->format('Y-m-d') }}
                                </td>
                                <td class="p-4 text-center">
                                    <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline px-2">ویرایش</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>

    @if($isFormOpen)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 mx-4">
                <h3 class="text-lg font-bold mb-4">{{ $selected_id ? 'ویرایش کاربر' : 'ثبت کاربر جدید' }}</h3>
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">نام کامل</label>
                        <input type="text" wire:model="name" class="w-full mt-1 border-gray-300 rounded-lg">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ایمیل</label>
                        <input type="email" wire:model="email" class="w-full mt-1 border-gray-300 rounded-lg">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">کلمه عبور {{ $selected_id ? '(در صورت تغییر وارد کنید)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full mt-1 border-gray-300 rounded-lg">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" wire:click="$set('isFormOpen', false)" class="text-gray-500 px-4 py-2">انصراف</button>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">ذخیره اطلاعات</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>