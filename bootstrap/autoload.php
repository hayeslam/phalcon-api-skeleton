<?php

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(ROOT_PATH);
$dotenv->safeLoad();

/**
 * @var $di \Phalcon\Di\DiInterface
 * @var $whoops \Whoops\Run
 */

// Require di
require ROOT_PATH . '/config/services.php';

// Register Whoops
$di['whoops']->register();
