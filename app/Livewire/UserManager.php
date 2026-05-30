<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $email, $password, $password_confirmation, $role, $selected_id;
    public $isFormOpen = false;

    // تعریف لیبل‌ها در اینجا برای جلوگیری از شلوغی ویو
    public $roleLabels = [
        'admin'      => 'مدیرکل',
        'manager'    => 'مدیر',
        'accountant' => 'حسابدار',
        'warehouse'  => 'انباردار',
    ];

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.user-manager', [
            'users' => $users,
            'roles' => $roles
        ])->layout('layouts.app');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->selected_id,
            'role' => 'required|exists:roles,name',
            'password' => $this->selected_id ? 'nullable|confirmed|min:8' : 'required|confirmed|min:8',
        ]);

        if ($this->selected_id) {
            $user = User::find($this->selected_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);
            $user->syncRoles($this->role); // همگام‌سازی نقش
            $msg = 'اطلاعات کاربر و دسترسی‌ها به‌روزرسانی شد.';
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->assignRole($this->role); // تخصیص نقش
            $msg = 'کاربر جدید با موفقیت ایجاد شد.';
        }

        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'selected_id', 'isFormOpen']);
        session()->flash('status', $msg);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name;
        $this->isFormOpen = true;
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('status', 'کاربر از سیستم حذف شد.');
    }
	
	public function index() {
    // اگر کاربر اجازه 'view users' را نداشت، خطای ۴۰۳ (عدم دسترسی) بده
    abort_if(!auth()->user()->can('view users'), 403, 'شما اجازه دسترسی به این بخش را ندارید');

    $users = \App\Models\User::all();
    return view('users.index', compact('users'));
}
}