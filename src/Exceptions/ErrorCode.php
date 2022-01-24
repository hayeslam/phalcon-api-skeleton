<?php

namespace App\Exceptions;

class ErrorCode
{
    const SYSTEM_ERROR = 500;
    const INVALID_PARAMETER = 400;

    public static function errorMessages(): array
    {
        return [
            self::SYSTEM_ERROR => 'system error.',
            self::INVALID_PARAMETER => 'invalid parameter.',
        ];
    }

    public static function asMessage(int $errorCode): string
    {
        $messages = self::errorMessages();

        return $messages[$errorCode] ?? 'unknown.';
    }
}
