<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware as JwtBaseMiddleware;

class JwtAuth extends JwtBaseMiddleware
{
    const TOKEN_NOT_PROVIDED = 'token-not-provided';
    const TOKEN_EXPIRED = 'token-expired';
    const TOKEN_INVALID = 'token-invalid';
    const TOKEN_USER_NOT_FOUND = 'token-user-not-found';

    public function getAuth()
    {
        return $this->auth;
    }
    public function handle($request, Closure $next)
    {
        $token = $this->getAuth()->setRequest($request)->getToken();

        if (!$token) {
            return response()->unauthorized(static::TOKEN_NOT_PROVIDED);
        }

        try {
            $user = $this->getAuth()->authenticate($token);
        } catch (TokenExpiredException $exception) {
            return response()->unauthorized(static::TOKEN_EXPIRED);
        } catch (JWTException $exception) {
            return response()->unauthorized(static::TOKEN_INVALID);
        }

        if (!$user) {
            return response()->unauthorized(static::TOKEN_USER_NOT_FOUND);
        }

        return $next($request);
    }
}
