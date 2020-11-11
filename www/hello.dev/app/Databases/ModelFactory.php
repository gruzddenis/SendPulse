<?php

namespace App\Databases;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserService
 *
 * @package App\Services
 */
class ModelFactory {

    /**
     * @param string $model
     *
     * @return Model
     */
    public function get(string $model): Model
    {
        return new $model;
    }

}
