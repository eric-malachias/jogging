<?php

namespace App\Models;

class JoggingTime extends Model
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
