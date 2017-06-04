<?php

namespace App\Models;

use Tests\TestCase;
use Mockery;

class UserTest extends TestCase
{
    public function isRoleProvider()
    {
        return [
            ['isAdmin'],
            ['isManager'],
            ['isRegular'],
        ];
    }
    /**
     * @dataProvider isRoleProvider
     */
    public function testIsAdminShouldUseIsRoleFromRoleModel($method)
    {
        $token = '12345';
        $role = Mockery::mock(Role::class)
            ->shouldReceive($method)
            ->once()
            ->andReturn($token)
            ->getMock();
        $user = Mockery::mock(User::class)->makePartial();
        $user
            ->shouldReceive('getAttribute')
            ->once()
            ->with('role')
            ->andReturn($role);

        $this->assertEquals($user->{$method}(), $token);
    }
}
