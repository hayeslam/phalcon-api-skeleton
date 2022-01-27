<?php

use Monolog\Logger;

return [
    'app' => [
        'env' => env('APP_ENV'),
        'name' => env('APP_ENV'),
        'debug' => env('APP_DEBUG'),
    ],
    'logger' => [
        'name' => 'main',
        'level' => Logger::DEBUG,
        'stream' => env('APP_LOG_FILE'),
    ],
    'database' => [
        'adapter' => env('DB_ADAPTER'),
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'dbname' => env('DB_DBNAME'),
        'charset' => env('DB_CHARSET'),
        'options' => [
            \PDO::ATTR_EMULATE_PREPARES => false,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]
    ],
];
