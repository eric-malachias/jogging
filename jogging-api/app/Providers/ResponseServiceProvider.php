<?php

namespace App\Providers;

use Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    protected function createResponse($name, $statusCode)
    {
        Response::macro($name, function ($response = null) use ($statusCode) {
            return Response::json($response, $statusCode);
        });
    }

    public function boot()
    {
        $this->createResponse('ok', 200);
        $this->createResponse('created', 201);
        $this->createResponse('badRequest', 400);
        $this->createResponse('unauthorized', 401);
        $this->createResponse('forbidden', 403);
        $this->createResponse('notFound', 404);
        $this->createResponse('unprocessableEntity', 422);
        $this->createResponse('serverError', 500);
    }
}
