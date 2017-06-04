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

    public function scopeAfter($query, $after)
    {
        $query->where('ended_at', '>=', $after);
    }
    public function scopeBefore($query, $before)
    {
        $query->where('started_at', '<=', $before);
    }
    public function scopeBetween($query, $before, $after)
    {
        $query
            ->when($before, function ($query) use ($before) {
                return $query->before($before);
            })
            ->when($after, function ($query) use ($after) {
                return $query->after($after);
            });
    }
    public function scopeLatestFirst($query)
    {
        $query->orderByDesc('started_at');
    }
}
