<?php

use App\Http\Controllers;
use App\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

/** @var Micro $app */
$default = new MicroCollection();
$default
    ->setHandler(Controllers\DefaultController::class, true)
    ->setPrefix('/default')
    ->get('/index', 'indexAction');

$app->get('/', function () use ($app) {
    return $app->response->redirect('/default/index');
});

$app->mount($default);
