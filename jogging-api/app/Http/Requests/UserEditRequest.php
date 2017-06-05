<?php

namespace App\Http\Requests;

class UserEditRequest extends Request
{
    protected function excludeEmailFromUpdate()
    {
        if (empty($this->user) || $this->method() !== 'PUT') {
            return;
        }

        return ',' . $this->user->id;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . $this->excludeEmailFromUpdate(),
            'password' => 'required|password',
        ];
    }
}
