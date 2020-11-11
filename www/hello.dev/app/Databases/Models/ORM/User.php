<?php

namespace App\Databases\Models\ORM;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Models\ORM
 */
class User extends Model
{
    public static $authUser;

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * @param string  $email
     *
     * @return User
     */
    public function getUserByCredentials(string $email): User
    {

        return $this->where('email', $email)->first();
    }

    /**
     * @return User
     */
    public static function getAuthUser(): User
    {
        return self::$authUser;
    }

    /**
     * @param User $user
     */
    public static function setAuthUser(User $user)
    {
        self::$authUser = $user;
    }
}
