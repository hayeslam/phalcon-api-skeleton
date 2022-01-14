<?php

use App\Http\Controllers;
use App\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/** @var Micro $app */
$app->get('/', function () {
    return 'hi.';
});

$default = new MicroCollection();
$default
    ->setHandler(Controllers\DefaultController::class, true)
    ->setPrefix('/default')
    ->get('/index', 'index');

$app->mount($default);
