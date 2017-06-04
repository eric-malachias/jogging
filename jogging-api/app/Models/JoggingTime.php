<?php

namespace App\Models;

use Auth;

class JoggingTime extends Model
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

        static::creating(function ($joggingTime) {
            if (empty($joggingTime->owner_id)) {
                $joggingTime->owner_id = Auth::user()->id;
            }
        });
    }
}
