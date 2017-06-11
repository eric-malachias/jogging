<?php

namespace App\Http\Controllers;

use Mockery;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

class UserControllerTest extends AbstractControllerTest
{
    protected function getController()
    {
        return Mockery::mock(UserController::class)->makePartial();
    }
    protected function getRandomEmail()
    {
        return sprintf('%s@example.com', str_random(100));
    }

    public function loginProvider()
    {
        $email = $this->getRandomEmail();
        $password = '123456';

        return [
            [$email, $password, $email, $password, 200],
            [$email, $password, 'not-' . $email, $password, 404],
            [$email, $password, $email, 'not-' . $password, 400],
        ];
    }
    /**
     * @dataProvider loginProvider
     */
    public function testLoginShouldRespondWithCorrectStatus($email, $password, $loginEmail, $loginPassword, $statusCode)
    {
        factory(User::class)->create([
            'email' => $email,
            'password' => $password,
        ]);

        $response = $this->call('POST', '/users/login', [
            'email' => $loginEmail,
            'password' => $loginPassword,
        ]);

        $this->assertEquals($statusCode, $response->status());
    }
    public function viewAllStatusProvider()
    {
        return [
            [Role::REGULAR, Response::HTTP_FORBIDDEN],
            [Role::MANAGER, Response::HTTP_OK],
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
    public function roleHierarchyStatusProvider()
    {
        return [
            [Role::REGULAR, Role::REGULAR, Response::HTTP_FORBIDDEN],
            [Role::REGULAR, Role::MANAGER, Response::HTTP_FORBIDDEN],
            [Role::REGULAR, Role::ADMIN, Response::HTTP_FORBIDDEN],
            [Role::MANAGER, Role::REGULAR, Response::HTTP_OK],
            [Role::MANAGER, Role::MANAGER, Response::HTTP_FORBIDDEN],
            [Role::MANAGER, Role::ADMIN, Response::HTTP_FORBIDDEN],
            [Role::ADMIN, Role::REGULAR, Response::HTTP_OK],
            [Role::ADMIN, Role::MANAGER, Response::HTTP_OK],
            [Role::ADMIN, Role::ADMIN, Response::HTTP_FORBIDDEN],
        ];
    }
    /**
     * @dataProvider roleHierarchyStatusProvider
     */
    public function testPutOneShouldRespondWithCorrectStatus($sourceRoleName, $targetRoleName, $statusCode)
    {
        $sourceUser = $this->createUser($sourceRoleName);
        $targetUser = $this->createUser($targetRoleName);

        $response = $this->callWithToken(
            $sourceUser,
            'PUT',
            '/users/' . $targetUser->id,
            $targetUser->getAttributes()
        );

        $this->assertEquals($statusCode, $response->status());
    }
    /**
     * @dataProvider roleHierarchyStatusProvider
     */
    public function testGetOneShouldRespondWithCorrectStatus($sourceRoleName, $targetRoleName, $statusCode)
    {
        $sourceUser = $this->createUser($sourceRoleName);
        $targetUser = $this->createUser($targetRoleName);

        $response = $this->callWithToken(
            $sourceUser,
            'GET',
            '/users/' . $targetUser->id,
            $targetUser->getAttributes()
        );

        $this->assertEquals($statusCode, $response->status());
    }
    /**
     * @dataProvider roleHierarchyStatusProvider
     */
    public function testDeleteOneShouldRespondWithCorrectStatus($sourceRoleName, $targetRoleName, $statusCode)
    {
        $sourceUser = $this->createUser($sourceRoleName);
        $targetUser = $this->createUser($targetRoleName);

        $response = $this->callWithToken(
            $sourceUser,
            'DELETE',
            '/users/' . $targetUser->id,
            $targetUser->getAttributes()
        );

        $this->assertEquals($statusCode, $response->status());
    }
    /**
     * @dataProvider roleHierarchyStatusProvider
     */
    public function testPostOneShouldRespondWithCorrectStatus($sourceRoleName, $targetRoleName, $statusCode)
    {
        $sourceUser = $this->createUser($sourceRoleName);
        $targetUser = $this->createUser($targetRoleName);

        $targetUser->delete();

        $response = $this->callWithToken(
            $sourceUser,
            'POST',
            '/users',
            $targetUser->getAttributes()
        );

        $this->assertEquals(
            $statusCode === Response::HTTP_OK ? Response::HTTP_CREATED : $statusCode,
            $response->status()
        );
    }
}
