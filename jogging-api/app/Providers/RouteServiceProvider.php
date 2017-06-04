<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('crud', function (string $entity, array $methods = ['get', 'post', 'put', 'delete', 'getAll']) {
            $urlWithoutId = sprintf('/%s', snake_case(str_plural($entity)));
            $urlWithId = sprintf('%s/{id}', $urlWithoutId);
            $controller = sprintf('%sController@', str_replace('-', '', title_case($entity)));

            if (in_array('get', $methods)) {
                Route::get($urlWithId, $controller . 'getOne');
            }
            if (in_array('getAll', $methods)) {
                Route::get($urlWithoutId, $controller . 'getAll');
            }
            if (in_array('put', $methods)) {
                Route::put($urlWithId, $controller . 'putOne');
            }
            if (in_array('post', $methods)) {
                Route::post($urlWithoutId, $controller . 'postOne');
            }
            if (in_array('delete', $methods)) {
                Route::delete($urlWithId, $controller . 'deleteOne');
            }
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
