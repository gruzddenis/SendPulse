<?php

namespace App\Exceptions;

use Exception;

/**
 * Class UnauthorizedException
 *
 * @package App\Exceptions
 */
class UnauthorizedException extends Exception
{
    /** @var int */
    protected $statusCode = 401;

    /** @var string */
    protected $message = "Unauthorized";

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
