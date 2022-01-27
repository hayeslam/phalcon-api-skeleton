<?php

ini_set('log_errors', 1);
ini_set('display_errors', 0);
ini_set('error_reporting', E_ALL);

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(ROOT_PATH);
$dotenv->safeLoad();

/**
 * Require di
 * @var $di \Phalcon\Di\DiInterface
 */
require ROOT_PATH . '/config/services.php';

// Register Whoops
$di['whoops']->register();
