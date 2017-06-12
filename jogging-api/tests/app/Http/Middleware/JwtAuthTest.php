<?php

namespace App\Http\Middleware;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtAuthTest extends TestCase
{
    protected function getMiddleware($token = null, $exception = null, $user = null)
    {
        $middleware = Mockery::mock(JwtAuth::class)->makePartial();
        $request = Mockery::mock()
            ->shouldReceive('getToken')
            ->once()
            ->andReturn($token)
            ->getMock();
        $auth = Mockery::mock(stdClass::class)->makePartial();
        $auth
            ->shouldReceive('setRequest')
            ->once()
            ->andReturn($request)
            ->getMock();

        if (!empty($exception)) {
            $auth
                ->shouldReceive('authenticate')
                ->andThrow(new $exception);
        } else {
            $auth
                ->shouldReceive('authenticate')
                ->andReturn($user);
        }

        $middleware
            ->shouldReceive('getAuth')
            ->andReturn($auth);

        return $middleware;
    }
    protected function getRequest()
    {
        return Mockery::mock(Request::class)->makePartial();
    }
    protected function getClosure()
    {
        return function ($foo) {

        };
    }

    public function testHandleShouldRespondWithTokenNotProvided()
    {
        $response = $this->getMiddleware('')->handle($this->getRequest(), $this->getClosure());

        $this->assertEquals(JwtAuth::TOKEN_NOT_PROVIDED, $response->getData());
    }
    public function testHandleShouldRespondWithInvalidToken()
    {
        $middleware = $this->getMiddleware('foo', JWTException::class);

        $response = $middleware->handle($this->getRequest(), $this->getClosure());

        $this->assertEquals(JwtAuth::TOKEN_INVALID, $response->getData());
    }
    public function testHandleShouldRespondWithExpiredToken()
    {
        $middleware = $this->getMiddleware('foo', TokenExpiredException::class);

        $response = $middleware->handle($this->getRequest(), $this->getClosure());

        $this->assertEquals(JwtAuth::TOKEN_EXPIRED, $response->getData());
    }
    public function testHandleShouldRespondWithUserNotFound()
    {
        $middleware = $this->getMiddleware('foo');

        $response = $middleware->handle($this->getRequest(), $this->getClosure());

        $this->assertEquals(JwtAuth::TOKEN_USER_NOT_FOUND, $response->getData());
    }
    public function testHandleShouldCallClosure()
    {
        $middleware = $this->getMiddleware('foo', null, true);
        $object = Mockery::mock()
            ->shouldReceive('method')
            ->once()
            ->getMock();

        $response = $middleware->handle($this->getRequest(), function () use ($object) {
            $object->method();
        });
    }
}
