<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    public function view(User $loggedUser, User $user)
    {
        return $loggedUser->hasMoreAccess($user) || $user->is($loggedUser);
    }
    public function edit(User $loggedUser, User $user)
    {
        return $loggedUser->hasMoreAccess($user) || $user->is($loggedUser);
    }
    public function delete(User $loggedUser, User $user)
    {
        return $loggedUser->hasMoreAccess($user) || $user->is($loggedUser);
    }
    public function create(User $loggedUser)
    {
        return $loggedUser->isManagerOrAdmin();
    }
    public function viewAll(User $loggedUser)
    {
        return $loggedUser->isManagerOrAdmin();
    }
}
