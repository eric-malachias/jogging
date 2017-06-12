<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy extends Policy
{
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
    public function viewAll(User $user)
    {
        return false;
    }
}
