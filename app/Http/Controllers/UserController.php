<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $users = \App\Models\User::with('roles')->latest()->paginate(10);
    
    // آرایه ترجمه
    $roleLabels = [
        'admin'      => 'مدیرکل',
        'manager'    => 'مدیر',
        'accountant' => 'حسابدار',
        'warehouse'  => 'انباردار',
    ];

    return view('users.index', compact('users', 'roleLabels'));
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $roles = \Spatie\Permission\Models\Role::all();

    return view('users.create', compact('roles'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|exists:roles,name'
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);
	
	activity()
    ->causedBy(auth()->user())
    ->performedOn($user)
    ->log('User Created');

    if(auth()->user()->hasRole('admin')){
        $user->assignRole($validated['role']);
    }

    return redirect()->route('users.index')
            ->with('success','کاربر ایجاد شد');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
{
    $roles = \Spatie\Permission\Models\Role::all();

    return view('users.edit', compact('user','roles'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(\Illuminate\Http\Request $request, \App\Models\User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
        'role' => 'required|exists:roles,name'
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
    ]);

    if($validated['password']){
        $user->update([
            'password' => bcrypt($validated['password'])
        ]);
    }

    if(auth()->user()->hasRole('admin')){
        $user->syncRoles([$validated['role']]);
    }

activity()
    ->causedBy(auth()->user())
    ->performedOn($user)
    ->log('User Updated');

    return redirect()->route('users.index')
            ->with('success','کاربر ویرایش شد');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(\App\Models\User $user)
{
    $user->delete();

activity()
    ->causedBy(auth()->user())
    ->performedOn($user)
    ->log('User Deleted');

    return redirect()->route('users.index')
        ->with('success','کاربر حذف شد');
}
}
