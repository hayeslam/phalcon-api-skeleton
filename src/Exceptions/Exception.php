<?php

namespace App\Exceptions;

use \Exception as BaseException;
use \Throwable;

class Exception extends BaseException implements ExceptionInterface
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        if ($code == 0) {
            $code = ErrorCode::SYSTEM_ERROR;
        }

        if (!$message) {
            $message = ErrorCode::asMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }

    public static function withCode(int $errorCode): Exception
    {
        return new static(null, $errorCode);
    }
}
