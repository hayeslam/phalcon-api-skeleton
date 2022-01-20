<?php

use App\Http\Controllers;
use App\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/** @var Micro $app */
$default = new MicroCollection();
$default
    ->setHandler(Controllers\DefaultController::class, true)
    ->setPrefix('/default')
    ->get('/index', 'indexAction');

$app->mount($default);
