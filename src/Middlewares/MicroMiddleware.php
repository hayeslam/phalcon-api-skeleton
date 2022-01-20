<?php

namespace App\Middlewares;

use App\Responder\ResponseResult;
use Phalcon\Mvc\Micro\MiddlewareInterface;

class MicroMiddleware implements MiddlewareInterface
{
    /**
     * Route not found
     *
     * @return false
     * @throws \App\Exceptions\Exception
     */
    public function beforeNotFound(): bool
    {
        ResponseResult::notFound()->send();

        return false;
    }

    /**
     * @param \Phalcon\Mvc\Micro $application
     * @return bool
     */
    public function call(\Phalcon\Mvc\Micro $application): bool
    {
        return true;
    }
}
