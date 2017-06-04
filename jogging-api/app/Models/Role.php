<?php

namespace App\Models;

class Role extends Model
{
    const ADMIN = 'admin';
    const MANAGER = 'manager';
    const REGULAR = 'regular';

    public function isRole($name)
    {
        return $this->name === $name;
    }
    public function isAdmin()
    {
        return $this->isRole(static::ADMIN);
    }
    public function isManager()
    {
        return $this->isRole(static::MANAGER);
    }
    public function isRegular()
    {
        return $this->isRole(static::REGULAR);
    }
}
