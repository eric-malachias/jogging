<?php

namespace App\Models;

use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

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
    public function testJogsShouldBeACollectionOfJogsBelongingToTheUser()
    {
        $user = factory(User::class)->create([
            'email' => str_random(100) . '@example.com',
        ]);
        $jogs = factory(Jog::class, 123)->create([
            'owner_id' => $user->id,
        ]);

        $this->assertEquals(123, $user->jogs->count());
    }
    public function testGetWeeklyReportShouldBeACollection()
    {
        $user = factory(User::class)->create([
            'email' => str_random(100) . '@example.com',
        ]);
        $jogs = factory(Jog::class, 123)->create([
            'owner_id' => $user->id,
        ]);

        $this->assertTrue($user->getWeeklyJogReport()->get()->count() > 0);
    }
}
