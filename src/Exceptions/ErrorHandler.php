<?php

namespace App\Exceptions;

use App\Responder\ResponseResult;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;

class ErrorHandler extends JsonResponseHandler
{
    /**
     * @return int|null
     */
    public function handle(): ?int
    {
        $this->log();
        // exception response
        ResponseResult::asException(
            $this->getErrorCode(),
            $this->getErrorMessage(),
            $this->getErrorContent(),
            $this->getHttpStatusCode()
        );

        return Handler::QUIT;
    }

    private function log()
    {
        /** @var LoggerInterface $logger */
        $logger = di('logger');
        $exception = $this->getException();
        if ($exception instanceof \ErrorException) {
            $level = LogLevel::ERROR;
        } elseif (!$exception instanceof ExceptionInterface) {
            $level = LogLevel::WARNING;
        } elseif ($exception instanceof ValidationException) {
            $level = LogLevel::DEBUG;
        } else {
            $level = LogLevel::INFO;
        }
        $logger->log($level, 'Catch exception.', ['exception' => $this->getException()]);
    }

    protected function getErrorCode(): int
    {
        $exception = $this->getException();
        return !$exception instanceof Exception || !$exception->getCode()
            ? ErrorCode::SYSTEM_ERROR
            : $exception->getCode();
    }

    protected function getErrorMessage(): string
    {
        $exception = $this->getException();
        return $exception instanceof ExceptionInterface
            ? $exception->getMessage()
            : ErrorCode::asMessage($this->getErrorCode());
    }

    protected function getErrorContent(): array
    {
        $content = [];
        $exception = $this->getException();
        // error detail
        if ($exception instanceof Exception) {
            $messages = $exception->getDetailMessages();
            if ($messages && $messages->count() > 0) {
                foreach ($messages as $message) {
                    $content['error_details'][] = [
                        'field' => $message->getField(),
                        'message' => $message->getMessage(),
                    ];
                }
            }
        }
        // exception trace
        $shouldAddTrace = $this->addTraceToOutput();
        if ($shouldAddTrace) {
            if (!$exception instanceof ExceptionInterface) {
                $content['debug']['code'] = $exception->getCode();
                $content['debug']['message'] = $exception->getMessage();
            }
            $content['debug']['trace'] = $this->getExceptionTrace();
        }

        return $content;
    }

    protected function getHttpStatusCode(): int
    {
        $exception = $this->getException();
        return $exception instanceof HttpException
            ? $exception->getStatusCode()
            : 500;
    }

    /**
     * Get exception trace.
     *
     * @return array
     */
    private function getExceptionTrace(): array
    {
        $inspector = $this->getInspector();
        $frames = $inspector->getFrames();
        $trace = [];
        foreach ($frames as $i => $frame) {
            $plain = "#" . (count($frames) - $i - 1) . " ";
            $plain .= $frame->getClass() ?: '';
            $plain .= $frame->getClass() && $frame->getFunction() ? ":" : "";
            $plain .= $frame->getFunction() ?: '';
            $plain .= ' in ';
            $plain .= ($frame->getFile() ?: '<#unknown>');
            $plain .= ':';
            $plain .= (int)$frame->getLine();

            $trace[] = $plain;

            if ($frame->getFunction() == 'withCode') {
                array_shift($trace);
            }
        }

        return $trace;
    }
}
