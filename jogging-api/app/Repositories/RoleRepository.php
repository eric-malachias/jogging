<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends Repository
{
    protected $model = Role::class;

    public function findByNameOrFail($name)
    {
        return $this->findByOrFail('name', $name);
    }
}
