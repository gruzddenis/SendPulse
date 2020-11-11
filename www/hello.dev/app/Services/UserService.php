<?php

namespace App\Services;

use App\Databases\ModelFactory;
use App\Databases\Models\ORM\User;
use App\Exceptions\ErrorMessages;
use App\Exceptions\InvalidRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserService
 *
 * @package App\Services
 */
class UserService
{
    /** @var UserService */
    protected $model;

    /**
     * UserService constructor.
     *
     * @param ModelFactory $model
     */
    public function __construct(ModelFactory $model)
    {
        $this->model =  $model;
    }

    /**
     * @param string $email
     * @param mixed $password
     *
     * @return User
     */
    public function create(string $email, $password): User
    {
        $model = $this->model->get(User::class);
        $model->email = $email;
        $model->password = $this->hasPassword($password);
        $model->save();

        return $model;
    }

    /**
     * @param string $email
     * @param mixed  $password
     *
     * @return string
     * @throws \Exception
     */
    public function login(string $email, $password): string
    {
        $model = $this->model->get(User::class);
        $user = $model->getUserByCredentials($email);

        if ($user === null) {
            throw new NotFoundHttpException(ErrorMessages::USER_NOT_FOUND);
        }

        if (!$this->checkCorrectPassword($password, $user->password)) {
            throw new InvalidRequestException(ErrorMessages::INVALID_PASSWORD);
        }

        if ($user->token == null) {
            $token = bin2hex(random_bytes(40));
            $user->token = $token;
            $user->save();
        }

       return $user->token;
    }

    /**
     * @param mixed $password
     *
     * @return mixed
     */
    public function hasPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param mixed $password
     * @param $hash
     *
     * @return mixed
     */
    public function checkCorrectPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
