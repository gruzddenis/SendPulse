<?php

namespace App\Middleware;

use App\Databases\Models\ORM\User;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ErrorMessages;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

/**
 * Class AuthorizationMiddleware
 *
 * @package App\Middleware
 */
class AuthorizationMiddleware
{
    /**
     * @param Request $request
     * @param Route $route
     *
     * @throws UnauthorizedException
     */
    public function handle(Request $request,Route $route)
    {
        if ($request->getMethod() == 'OPTIONS' || $route->uri == 'user/login' || $route->uri == 'user') {
            return;
        } else {

            $token = $request->header('Auth-Token');

            if (!$token){
                throw new UnauthorizedException(ErrorMessages::UNAUTHORIZED);
            }

            $user = User::whereToken($token)->first();

            if ($user == null) {
                throw new UnauthorizedException(ErrorMessages::UNAUTHORIZED);
            }

            User::setAuthUser($user);
        }
    }
}
