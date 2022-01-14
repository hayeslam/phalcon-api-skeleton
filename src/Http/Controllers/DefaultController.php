<?php

namespace App\Http\Controllers;

use App\Responder\ResponseResult;
use Phalcon\Http\ResponseInterface;

class DefaultController extends BaseController
{
    public function index(): ResponseInterface
    {
        return ResponseResult::success([$this->router->getMatchedRoute()->getPattern()]);
    }
}
