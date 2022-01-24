<?php

namespace App\Mvc;

use App\Injectable;

abstract class Controller extends Injectable implements \Phalcon\Mvc\ControllerInterface
{
    /**
     * Phalcon\Mvc\Controller constructor
     */
    final public function __construct()
    {
    }
}
