<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    /**
     * @var int The status code
     */
    private $statusCode;

    /**
     * @var mixed The error details
     */
    private $errorDetails;

    public function __construct(string $message, mixed $errorDetails, int $statusCode = 500)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
        $this->errorDetails = $errorDetails;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getErrorDetails() : mixed
    {
        return $this->errorDetails;
    }
}