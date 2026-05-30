<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            مدیریت کاربران
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end mb-4">
                <a href="{{ route('users.create') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    افزودن کاربر
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="min-w-full text-sm text-right">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">نام</th>
                                <th class="px-4 py-2">ایمیل</th>
                                <th class="px-4 py-2">نقش</th>
                                <th class="px-4 py-2">عملیات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $user->name }}</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                    <td class="px-4 py-2">
                                       {{ $user->role_label }}
                                    </td>
                                    <td class="px-4 py-2 flex gap-2">
<a href="{{ route('users.edit', $user) }}"
class="text-indigo-600 hover:underline">
ویرایش
</a>

<form method="POST" action="{{ route('users.destroy',$user) }}">
@csrf
@method('DELETE')
<button class="text-red-600 hover:underline">
حذف
</button>
</form>
</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>