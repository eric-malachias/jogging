<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    public function before($user, $ability)
    {
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }
    }
    public function view(User $user, User $loggedUser)
    {
        return $user->is($loggedUser);
    }
    public function edit(User $user, User $loggedUser)
    {
        return $user->is($loggedUser);
    }
    public function delete(User $user, User $loggedUser)
    {
        return $user->is($loggedUser);
    }
    public function create(User $loggedUser)
    {
        return false;
    }
    public function viewAll(User $loggedUser)
    {
        return false;
    }
}
