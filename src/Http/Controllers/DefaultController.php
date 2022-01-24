<?php

namespace App\Http\Controllers;

use App\Responder\ResponseResult;
use App\Validation\Validation;
use Phalcon\Http\ResponseInterface;

class DefaultController extends BaseController
{
    public function indexAction(): ResponseInterface
    {
        return ResponseResult::asSuccess([$this->router->getMatchedRoute()->getPattern()]);
    }
}
