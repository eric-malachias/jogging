<?php

namespace App\Models;

use Auth;

class Jog extends Model
{
    protected $fillable = [
        'started_at',
        'ended_at',
        'distance',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($jog) {
            if (empty($jog->owner_id)) {
                $jog->owner_id = Auth::user()->id;
            }
        });
    }
}
