<?php

namespace App\Exceptions;

use Throwable;

class HttpException extends Exception
{
    protected int $statusCode;

    public function __construct(int $statusCode, $message = '', $code = 0, Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public static function notFound($message = 'URI not found.', $code = 404): Throwable
    {
        return (new static(404, $message, $code));
    }
}
