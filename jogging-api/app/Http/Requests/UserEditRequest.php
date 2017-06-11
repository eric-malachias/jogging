<?php

namespace App\Http\Requests;

use App\Models\User;

class UserEditRequest extends Request
{
    protected function excludeEmailFromUpdate()
    {
        if (empty($this->user) || $this->method() !== 'PUT') {
            return;
        }

        return ',' . $this->user->id;
    }

    public function authorize()
    {
        if (!empty($this->user) && $this->user()->is($this->user)) {
            return true;
        }

        $roleId = $this->role_id;

        if (empty($roleId)) {
            return true;
        }

        $targetUser = app(User::class);
        $targetUser->role_id = $roleId;

        return $this->user()->hasMoreAccess($targetUser);
    }
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . $this->excludeEmailFromUpdate(),
            'password' => 'required|password',
            'role_id' => 'exists:roles,id',
        ];
    }
}
