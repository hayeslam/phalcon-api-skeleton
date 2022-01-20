<?php

use Monolog\Logger;

return [
    'app' => [
        'env' => 'development',
        'name' => 'api',
        'debug' => true,
    ],
    'logger' => [
        'name' => 'main',
        'level' => Logger::DEBUG,
        'stream' => getenv('APP_LOG_DIR') . '/app.log',
    ],
    'database' => [
        'adapter' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'username' => 'root',
        'password' => '123123',
        'dbname' => 'vbt',
        'charset' => 'utf8mb4',
        'options' => [
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ],
];
