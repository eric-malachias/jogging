<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Repositories\RoleRepository;
use Hash;
use App\Repositories\JogRepository;

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
    public function jogs()
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
    public function isManagerOrAdmin()
    {
        return $this->isManager() || $this->isAdmin();
    }
    public function isRegular()
    {
        return $this->role->isRegular();
    }
    public function hasMoreAccess($user)
    {
        if ($this->isAdmin() && !$user->isAdmin()) {
            return true;
        }
        if ($this->isManager() && $user->isRegular()) {
            return true;
        }

        return false;
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
    public function owns(Jog $jog)
    {
        return (int)$jog->owner_id === (int)$this->id;
    }
    public function getWeeklyJogReport()
    {
        return app(JogRepository::class)
            ->getWeeklyReport()
            ->byOwner($this->id);
    }
    public function scopeIsRole($query, array $roleNames)
    {
        $query->whereHas('role', function ($query) use ($roleNames) {
            $query->whereIn('name', $roleNames);
        });
    }
    public function scopeIsRegular($query)
    {
        $query->isRole([Role::REGULAR]);
    }
    public function scopeIsRegularOrManager($query)
    {
        $query->isRole([Role::REGULAR, Role::MANAGER]);
    }
    public function scopeOrderByName($query)
    {
        $query->orderBy('name');
    }
}
