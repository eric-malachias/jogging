<?php

namespace App\Http\Requests;

class JoggingTimeRequest extends Request
{
    public function rules()
    {
        return [
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'distance' => 'required|numeric|min:1'
        ];
    }
}
