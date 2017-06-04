<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Repositories\RoleRepository;
use Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function Jogs()
    {
        return $this->hasMany(Jog::class, 'owner_id');
    }

    public function isAdmin()
    {
        return $this->role->isAdmin();
    }
    public function isManager()
    {
        return $this->role->isManager();
    }
    public function isRegular()
    {
        return $this->role->isRegular();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->role_id)) {
                $user->role_id = app(RoleRepository::class)->findRegular()->id;
            }
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
