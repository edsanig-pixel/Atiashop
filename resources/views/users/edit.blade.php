<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
ویرایش کاربر
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm sm:rounded-lg p-6">

<form method="POST" action="{{ route('users.update',$user) }}">
@csrf
@method('PUT')

<div class="mb-4">
<x-input-label value="نام"/>
<x-text-input name="name" value="{{ $user->name }}" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="ایمیل"/>
<x-text-input name="email" value="{{ $user->email }}" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="رمز جدید (اختیاری)"/>
<x-text-input type="password" name="password" class="w-full"/>
</div>

<div class="mb-4">
<x-input-label value="تکرار رمز"/>
<x-text-input type="password" name="password_confirmation" class="w-full"/>
</div>

<div class="mb-4">
    <x-input-label value="نقش"/>
    <select name="role" class="w-full border-gray-300 rounded-md">
        @php
            $labels = ['admin' => 'مدیرکل', 'manager' => 'مدیر', 'accountant' => 'حسابدار', 'warehouse' => 'انباردار'];
        @endphp
        @foreach($roles as $role)
            <option value="{{ $role->name }}" @if($user->roles->pluck('name')->contains($role->name)) selected @endif>
                {{ $labels[$role->name] ?? $role->name }}
            </option>
        @endforeach
    </select>
</div>

<x-primary-button>بروزرسانی</x-primary-button>

</form>

</div>
</div>
</div>
</x-app-layout>