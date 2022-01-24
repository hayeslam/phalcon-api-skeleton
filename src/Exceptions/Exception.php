<?php

namespace App\Exceptions;

use Exception as BaseException;
use Phalcon\Messages\Messages;
use Throwable;

class Exception extends BaseException implements ExceptionInterface
{
    protected ?Messages $detailMessages = null;

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

    /**
     * @param Messages $messages
     * @return void
     */
    public function setDetailMessages(Messages $messages)
    {
        $this->detailMessages = $messages;
    }

    /**
     * @return Messages
     */
    public function getDetailMessages(): ?Messages
    {
        return $this->detailMessages;
    }

    /**
     * @param int $errorCode
     * @return Exception
     */
    public static function withCode(int $errorCode): Exception
    {
        return new static(null, $errorCode);
    }
}
