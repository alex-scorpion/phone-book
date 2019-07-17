<?php

use Monolog\Logger;
use Monolog\Handler;

return [
    'logger' => [
        'use' => [Handler\StreamHandler::class],
        'handlers' => [
            Handler\StreamHandler::class => [
                base_path().'/storage/logs/app.log',
                env('APP_DEBUG') ? Logger::DEBUG : Logger::WARNING
            ]
        ]
    ]
];