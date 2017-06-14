<?php

namespace App\Http\Controllers;

use Mockery;
use App\Models\Jog;
use App\Repositories\JogRepository;
use App\Models\Role;
use Illuminate\Http\Response;

class JogControllerTest extends AbstractControllerTest
{
    protected function getController()
    {
        return Mockery::mock(JogController::class)->makePartial();
    }
    protected function createJog($user = null)
    {
        if (empty($user)) {
            $user = $this->createUser();
        }

        return factory(Jog::class)->create([
            'owner_id' => $user->id,
        ]);
    }

    public function testGetOneShouldReturnTheCorrectRecord()
    {
        $user = $this->createUser();
        $jog = $this->createJog($user);

        $response = $this->callWithToken($user, 'GET', '/jogs/' . $jog->id);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals($jog->id, $response->getData()->id);
    }
    public function forbiddenUsersProvider()
    {
        return [
            ['GET', Role::REGULAR, Response::HTTP_FORBIDDEN],
            ['GET', Role::MANAGER, Response::HTTP_FORBIDDEN],
            ['GET', Role::ADMIN, Response::HTTP_OK],
            ['PUT', Role::REGULAR, Response::HTTP_FORBIDDEN],
            ['PUT', Role::MANAGER, Response::HTTP_FORBIDDEN],
            ['PUT', Role::ADMIN, Response::HTTP_BAD_REQUEST],
            ['DELETE', Role::REGULAR, Response::HTTP_FORBIDDEN],
            ['DELETE', Role::MANAGER, Response::HTTP_FORBIDDEN],
            ['DELETE', Role::ADMIN, Response::HTTP_OK],
        ];
    }
    /**
     * @dataProvider forbiddenUsersProvider
     */
    public function testActionsShouldBeForbiddenForNonOwners($method, $roleName, $statusCode)
    {
        $user = $this->createUser($roleName);
        $jog = $this->createJog();

        $response = $this->callWithToken($user, $method, '/jogs/' . $jog->id);

        $this->assertEquals($statusCode, $response->status());
    }
    public function testDeleteOneShouldDeleteSelectedRecord()
    {
        $user = $this->createUser();
        $jog = $this->createJog($user);

        $response = $this->callWithToken($user, 'DELETE', '/jogs/' . $jog->id);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertNull(app(JogRepository::class)->find($jog->id));
    }
    public function testPutOneShouldUpdateSelectedRecord()
    {
        $user = $this->createUser();
        $jog = $this->createJog($user);

        $jog->distance += 100;
        $jog->started_at->addDays(10);
        $jog->ended_at->addDays(10);

        $response = $this->callWithToken($user, 'PUT', '/jogs/' . $jog->id, $jog->toArray());

        $this->assertEquals(Response::HTTP_OK, $response->status());

        $data = $response->getData();
        $jogData = json_decode(json_encode($jog));

        $this->assertEquals($jogData->distance, $data->distance);
        $this->assertEquals($jogData->started_at, $data->started_at);
        $this->assertEquals($jogData->ended_at, $data->ended_at);
    }
    public function testPostOneShouldCreateARecord()
    {
        $user = $this->createUser();
        $jog = $this->createJog($user);
        $count = app(JogRepository::class)->count();

        $response = $this->callWithToken($user, 'POST', '/jogs', $jog->toArray());

        $this->assertEquals(Response::HTTP_CREATED, $response->status());
        $this->assertEquals($count + 1, app(JogRepository::class)->count());

        $data = $response->getData();
        $jogData = json_decode(json_encode($jog));

        $this->assertEquals([
            'distance' => $jogData->distance,
            'started_at' => $jogData->started_at,
            'ended_at' => $jogData->ended_at,
        ], [
            'distance' => $data->distance,
            'started_at' => $data->started_at,
            'ended_at' => $data->ended_at,
        ]);
    }
    public function getAllResponseForRolesProvider()
    {
        return [
            [Role::REGULAR, Response::HTTP_FORBIDDEN],
            [Role::MANAGER, Response::HTTP_FORBIDDEN],
            [Role::ADMIN, Response::HTTP_OK],
        ];
    }
    /**
     * @dataProvider getAllResponseForRolesProvider
     */
    public function testGetAllShouldRespondWithSuccessForAdminsAndForbiddenForOthers($roleName, $statusCode)
    {
        $user = $this->createUser($roleName);

        $response = $this->callWithToken($user, 'GET', '/jogs/');

        $this->assertEquals($statusCode, $response->status());
    }
}
