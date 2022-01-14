<?php

use App\Exceptions\ErrorHandler;
use Phalcon\Config;
use Phalcon\Events\Manager;
use Whoops\Run as WhoopsRun;

$di = new Phalcon\Di\FactoryDefault();

$di->setShared('config', function () {
    $config = require_once __DIR__ . '/config.php';

    return (new Config($config));
});

$di->setShared('eventsManager', function () {
    $eventsManager = new Manager();
    $eventsManager->enablePriorities(true);
    $eventsManager->collectResponses(true);

    return $eventsManager;
});

$di->setShared('whoops', function () {
    $errorHandler = new ErrorHandler;
    $errorHandler->addTraceToOutput(true);
    $whoops = new WhoopsRun;
    $whoops->appendHandler($errorHandler);

    return $whoops;
});
