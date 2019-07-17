<?php

use Core\Middleware as CoreMiddleware;
use App\Http\Middleware as AppMiddleware;

/**
 * Middleware выполняються в порядке заполнения
 */
return [
    'middleware' => [
        CoreMiddleware\ErrorHandlerMiddleware::class,
        CoreMiddleware\ErrorResponseMiddleware::class,
        AppMiddleware\ProfilerTimeMiddleware::class,
        CoreMiddleware\BodyParamsMiddleware::class,
        CoreMiddleware\RouteMiddleware::class,
    ]
];