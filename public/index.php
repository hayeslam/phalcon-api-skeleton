<?php

use Phalcon\Di\DiInterface;

/**
 * @var $dotenv Dotenv\Dotenv
 * @var $di DiInterface
 */
require '../bootstrap/autoload.php';

$app = new App\Micro($di);
$app->handle($_SERVER['REQUEST_URI']);
