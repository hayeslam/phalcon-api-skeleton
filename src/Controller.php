<?php

namespace App;

abstract class Controller extends Injectable implements \Phalcon\Mvc\ControllerInterface
{
    /**
     * Phalcon\Mvc\Controller constructor
     */
    final public function __construct()
    {
    }
}
