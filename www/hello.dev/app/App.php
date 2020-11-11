<?php

namespace App;

use App\Config\Config;
use App\Exceptions\Handler;
use App\Middleware\AuthorizationMiddleware;
use App\Routes\Routes;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\RoutingServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Container\Container;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Class App
 *
 * @package App
 */
class App
{
    /** @var Container */
    protected $app;

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function handle(): JsonResponse
    {
        $request = $this->app->make('request');
        $router = $this->app->make('router');
        Routes::register($router);
        $route = $router->getRoutes()->match($request);

        try {
            (new AuthorizationMiddleware())->handle($request, $route);
            $response = $route->run();
        } catch (\Exception $exception) {
            $response = (new Handler())->render($exception);
        }

        return $response;
    }

    public function boot()
    {
        $this->createApp();
        $this->initDatabase();
        $this->bindRequest();
        $this->registerProviders();

    }

    private function initDatabase()
    {
        $factory = new ConnectionFactory($this->app);
        $resolver = new DatabaseManager($this->app, $factory);
        Model::setConnectionResolver($resolver);
        $capsule = new Manager($this->app);
        $capsule->addConnection(Config::getConnectionParameters());
    }

    private function bindRequest()
    {
        $this->app->bind('request',function () {
            if (class_exists(Request::class)) {
                return (new Request())->capture();
            }
        });
        $this->app->bind(Request::class,function () {
            if (class_exists(Request::class)) {
                return (new Request())->capture();
            }
        });
    }

    private function registerProviders()
    {
        (new EventServiceProvider($this->app))->register();
        (new RoutingServiceProvider($this->app))->register();
    }

    private function createApp()
    {
        $this->app = Container::getInstance();
    }
}
