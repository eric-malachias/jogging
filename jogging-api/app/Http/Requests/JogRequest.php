<?php

namespace App\Http\Requests;

class JogRequest extends Request
{
    public function authorize()
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            return true;
        }

        $ownerId = $this->owner_id;

        return (int)$user->id === (int)$ownerId;
    }
    public function rules()
    {
        return [
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'distance' => 'required|numeric|min:1',
            'owner_id' => 'required|numeric|exists:users,id',
        ];
    }
}
