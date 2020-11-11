<?php

namespace App\Exceptions;

use Exception;

/**
 * Class InvalidRequestException
 *
 * @package App\Exceptions
 */
class InvalidRequestException extends Exception
{
    /** @var int */
    protected $statusCode;

    /** @var string */
    protected $message;

    public function __construct(string $message, $statusCode = 400)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        parent::__construct($this->message, $this->statusCode);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
