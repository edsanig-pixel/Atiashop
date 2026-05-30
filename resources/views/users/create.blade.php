<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
ایجاد کاربر
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm sm:rounded-lg p-6">

<form method="POST" action="{{ route('users.store') }}">
@csrf

<div class="mb-4">
<x-input-label value="نام"/>
<x-text-input name="name" class="w-full"/>
<x-input-error :messages="$errors->get('name')"/>
</div>

<div class="mb-4">
<x-input-label value="ایمیل"/>
<x-text-input name="email" class="w-full"/>
<x-input-error :messages="$errors->get('email')"/>
</div>

<div class="mb-4">
<x-input-label value="رمز عبور"/>
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
            <option value="{{ $role->name }}">{{ $labels[$role->name] ?? $role->name }}</option>
        @endforeach
    </select>
</div>

<x-primary-button>ثبت</x-primary-button>

</form>

</div>
</div>
</div>
</x-app-layout>