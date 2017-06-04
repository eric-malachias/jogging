<?php

namespace App\Http\Requests;

class UserSignUpRequest extends Request
{
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|password',
        ];
    }
}
