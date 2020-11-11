<?php

namespace App\Controllers;

use App\Databases\Models\ORM\User;
use App\Exceptions\InvalidRequestException;
use App\Exceptions\ErrorMessages;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserController
 *
 * @package App\Controllers
 */
class UserController extends Controller
{
    /** @var UserService */
    protected $service;

    /**
     * UserController constructor.
     *
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws InvalidRequestException
     */
    public function create(Request $request): JsonResponse
    {
        if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidRequestException(ErrorMessages::INVALID_EMAIL);
        }

        if (User::whereEmail($request->get('email'))->value('email')) {
            throw new InvalidRequestException(ErrorMessages::EMAIL_EXIST);
        }

        $user = $this->service->create($request->get('email'), $request->get('password'));

        return $this->response($user, 201);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $token = $this->service->login($request->get('email'), $request->get('password'));

        return $this->response($token);
    }
}
