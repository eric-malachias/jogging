<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    protected $model = User::class;

    public function findByEmailOrFail($email)
    {
        return $this->findByOrFail('email', $email);
    }
}
