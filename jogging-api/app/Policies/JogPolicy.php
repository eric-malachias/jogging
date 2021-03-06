<?php

namespace App\Policies;

use App\Models\Jog;
use App\Models\User;

class JogPolicy extends Policy
{
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }
    public function view(User $user, Jog $jog)
    {
        return $user->owns($jog);
    }
    public function edit(User $user, Jog $jog)
    {
        return $user->owns($jog);
    }
    public function delete(User $user, Jog $jog)
    {
        return $user->owns($jog);
    }
    public function create(User $user)
    {
        return $user->isRegular();
    }
    public function viewAll(User $user)
    {
        return false;
    }
}
