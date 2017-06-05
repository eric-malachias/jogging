<?php

namespace App\Providers;

use Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as HttpResponse;

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
        $this->createResponse('ok', HttpResponse::HTTP_OK);
        $this->createResponse('created', HttpResponse::HTTP_CREATED);
        $this->createResponse('badRequest', HttpResponse::HTTP_BAD_REQUEST);
        $this->createResponse('unauthorized', HttpResponse::HTTP_UNAUTHORIZED);
        $this->createResponse('forbidden', HttpResponse::HTTP_FORBIDDEN);
        $this->createResponse('notFound', HttpResponse::HTTP_NOT_FOUND);
        $this->createResponse('unprocessableEntity', HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        $this->createResponse('serverError', HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
