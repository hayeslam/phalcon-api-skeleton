<?php

namespace App\Exceptions;

use App\Responder\ResponseResult;
use Whoops\Handler\Handler;
use Whoops\Handler\JsonResponseHandler;


class ErrorHandler extends JsonResponseHandler
{
    public function handle(): ?int
    {
        $content = [];
        // debug trace
        $shouldAddTrace = $this->addTraceToOutput();
        if ($shouldAddTrace) {
            $content['debug']['error'] = $this->getException()->getMessage();
            $content['debug']['trace'] = $this->getExceptionTrace();
        }
        $responseResult = new ResponseResult(
            $this->getCode(),
            $this->getMessage(),
            $content,
            $this->getHttpStatusCode(),
        );
        $response = $responseResult->getResponse();
        $response->setJsonContent($responseResult->getPayload())->send();

        return Handler::QUIT;
    }

    protected function getCode(): int
    {
        $exception = $this->getException();
        if ($exception instanceof \ErrorException) {
            $code = ErrorCode::SYSTEM_ERROR;
        } else {
            $code = $this->getException()->getCode() ?: ErrorCode::SYSTEM_ERROR;
        }

        return $code;
    }

    protected function getMessage(): string
    {
        $exception = $this->getException();
        return $exception instanceof ExceptionInterface
            ? $exception->getMessage()
            : ErrorCode::asMessage($this->getCode());
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
            $plain .= (int) $frame->getLine();

            $trace[] = $plain;

            if ($frame->getFunction() == 'withCode') {
                array_shift($trace);
            }
        }

        return $trace;
    }
}
