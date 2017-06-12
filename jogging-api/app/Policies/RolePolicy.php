<?php

namespace App\Policies;

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
