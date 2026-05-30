<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->useLogName('user_management') // نام لاگ را مشخص‌تر کردم
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs(); // اگر تغییری نکرد لاگ ننداز
    }
	
	public function getRoleLabelAttribute()
{
    $labels = [
        'admin'      => 'مدیرکل',
        'manager'    => 'مدیر',
        'accountant' => 'حسابدار',
        'warehouse'  => 'انباردار',
    ];
    
    return $labels[$this->roles->first()->name] ?? 'بدون نقش';
}
}