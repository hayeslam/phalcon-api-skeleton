<?php

namespace App\Exceptions;

class ErrorCode
{
    const SYSTEM_ERROR = 500;

    public static function errorMessages(): array
    {
        return [
            self::SYSTEM_ERROR => 'system error.',
        ];
    }

    public static function asMessage(int $errorCode): string
    {
        $messages = self::errorMessages();

        return $messages[$errorCode] ?? 'unknown.';
    }
}
