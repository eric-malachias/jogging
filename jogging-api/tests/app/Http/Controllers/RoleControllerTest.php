<?php

namespace App\Http\Controllers;

use Mockery;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

class RoleControllerTest extends AbstractControllerTest
{
    protected function getController()
    {
        return Mockery::mock(RoleController::class)->makePartial();
    }

    public function viewAllStatusProvider()
    {
        return [
            [Role::REGULAR, Response::HTTP_FORBIDDEN],
            [Role::MANAGER, Response::HTTP_FORBIDDEN],
            [Role::ADMIN, Response::HTTP_OK],
        ];
    }
    /**
     * @dataProvider viewAllStatusProvider
     */
    public function testGetAllShouldRespondWithCorrectStatus($roleName, $statusCode)
    {
        $user = $this->createUser($roleName);
        $token = $this->getToken($user);

        $response = $this->callWithToken($user, 'GET', '/users');

        $this->assertEquals($statusCode, $response->status());
    }
}
