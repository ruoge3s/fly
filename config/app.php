<?php

return [
    'redis' => [
        'host'      => env('REDIS_HOST', '127.0.0.1'),
        'port'      => env('REDIS_PORT', 6379),
        'password'  => env('REDIS_PASSWORD', ''),
        'db'        => env('REDIS_DB', 0),
    ],
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'name' => env('DB_NAME', 'test'),
        'user' => env('DB_USER', 'root'),
        'port' => env('DB_PORT', 3306),
        'charset' => env('DB_CHARSET', 'utf8')
    ],
    'mail' => [
        'host'      => env('MAIL_HOST', 'smtp.qq.com'),
        'port'      => env('MAIL_PORT', '465'),
        'encry'     => env('MAIL_ENCRY', 'ssl'),
        'username'  => env('MAIL_USERNAME', 'fly@qq.com'),
        'password'  => env('MAIL_PASSWORD', '12345678')
    ]
];
