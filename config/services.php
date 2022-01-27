<?php

use App\Exceptions\ErrorHandler;
use App\Middlewares\DbMiddleware;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Phalcon\Config;
use Phalcon\Db\Adapter\PdoFactory;
use Phalcon\Db\Profiler as DbProfiler;
use Phalcon\Events\Manager;
use Whoops\Run as WhoopsRun;

$di = new Phalcon\Di\FactoryDefault();

$di->setShared('config', function () {
    $config = require_once ROOT_PATH . '/config/config.php';

    return (new Config($config));
});

$di->setShared('eventsManager', function () {
    $eventsManager = new Manager();
    $eventsManager->enablePriorities(true);
    $eventsManager->collectResponses(true);

    return $eventsManager;
});

$di->setShared('whoops', function () {
    $addTrace = is_debug() && environment('development');
    $errorHandler = new ErrorHandler;
    $errorHandler->addTraceToOutput($addTrace);
    $whoops = new WhoopsRun;
    $whoops->appendHandler($errorHandler);

    return $whoops;
});

$di->setShared('logger', function () {
    $config = \config('logger');
    $format = "[%datetime%][%extra.uid%] %channel%.%level_name%: %message% %context% are logged at %extra.file%:%extra.line%\n";
    $formatter = new LineFormatter($format, null, false, true);
    $streamHandler = new StreamHandler($config->stream, $config->level);
    $streamHandler->setFormatter($formatter);

    return new Logger($config->name, [$streamHandler], [new UidProcessor(), new IntrospectionProcessor()]);
});

$di->setShared('db', function () use ($di) {
    $config = config('database');
    $adapter = $config->get('adapter');
    $options = $config->toArray();
    unset($options['adapter']);

    /** @var Manager $em */
    $em = $di->getShared('eventsManager');
    $em->attach('db', new DbMiddleware());

    $connection = (new PdoFactory())->newInstance($adapter, $options);
    $connection->setEventsManager($em);

    return $connection;
});

$di->setShared('dbProfiler', function() {
    return new DbProfiler;
});
