<?php

namespace App\Models;

use Tests\TestCase;
use App\Repositories\RoleRepository;

class RoleTest extends TestCase
{
    protected function findByName($name)
    {
        return app(RoleRepository::class)->findByNameOrFail($name);
    }

    public function isRoleProvider()
    {
        return [
            [Role::ADMIN],
            [Role::MANAGER],
            [Role::REGULAR],
        ];
    }
    /**
     * @dataProvider isRoleProvider
     */
    public function testIsRoleShouldReturnForEqualNames($name)
    {
        $role = $this->findByName($name);

        $this->assertTrue($role->isRole($name));
    }
    public function isAdminProvider()
    {
        return [
            [Role::ADMIN, true],
            [Role::MANAGER, false],
            [Role::REGULAR, false],
        ];
    }
    /**
     * @dataProvider isAdminProvider
     */
    public function testIsAdminShouldReturnWhenNameIsAdmin($name, $result)
    {
        $role = $this->findByName($name);

        $this->assertEquals($role->isAdmin(), $result);
    }
    public function isManagerProvider()
    {
        return [
            [Role::ADMIN, false],
            [Role::MANAGER, true],
            [Role::REGULAR, false],
        ];
    }
    /**
     * @dataProvider isManagerProvider
     */
    public function testIsManagerShouldReturnWhenNameIsManager($name, $result)
    {
        $role = $this->findByName($name);

        $this->assertEquals($role->isManager(), $result);
    }
    public function isRegularProvider()
    {
        return [
            [Role::ADMIN, false],
            [Role::MANAGER, false],
            [Role::REGULAR, true],
        ];
    }
    /**
     * @dataProvider isRegularProvider
     */
    public function testIsRegularShouldReturnWhenNameIsRegular($name, $result)
    {
        $role = $this->findByName($name);

        $this->assertEquals($role->isRegular(), $result);
    }
}
