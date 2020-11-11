<?php

namespace App\Config;

/**
 * Class Config
 *
 * @package App\Config
 */
final class Config
{
    /**
     * @return array
     */
    public static function getConnectionParameters(): array
    {
        return [
            'driver'      => 'mysql',
            'host'        => 'mysql',
            'database'    => 'fap_dev',
            'username'    => 'root',
            'password'    => 'dbpass',
        ];
    }
}