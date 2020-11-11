<?php

namespace App\Routes;

use App\Controllers\ToDoController;
use App\Controllers\UserController;
use Illuminate\Routing\Router;

/**
 * Class Routes
 *
 * @package App\Routes
 */
class Routes
{
    /**
     * @param Router $router
     */
    public static function register(Router $router)
    {
        $router->get('/tasks', ToDoController::class . '@index');
        $router->post('/tasks', ToDoController::class . '@create');
        $router->put('/tasks/{taskId}', ToDoController::class . '@update');
        $router->patch('/tasks/{taskId}', ToDoController::class . '@close');
        $router->delete('/tasks/{taskId}', ToDoController::class . '@delete');
        $router->post('user/', UserController::class . '@create');
        $router->post('user/login', UserController::class . '@login');
    }
}
