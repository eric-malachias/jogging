<?php

namespace App\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Role;
use JWTAuth;
use App\Repositories\RoleRepository;

abstract class AbstractControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function createUser($roleName = Role::REGULAR)
    {
        return factory(User::class)->create([
            'role_id' => app(RoleRepository::class)->findByNameOrFail($roleName),
        ]);
    }
    protected function getToken($user)
    {
        return 'Bearer ' . JWTAuth::fromUser($user);
    }
    protected function callWithToken($user, $method, $url, $parameters = [])
    {
        return $this->call($method, $url, $parameters, [], [], [
            'HTTP_Authorization' => $this->getToken($user),
        ]);
    }
}
