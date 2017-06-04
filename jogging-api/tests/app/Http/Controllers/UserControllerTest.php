<?php

namespace App\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use App\Models\User;
use App\Http\Requests\Request;
use App\Repositories\UserRepository;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

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
}
