<?php

$db = [
    'driver'   => env('DB_DRIVER', 'mysql'),
    'host'     => env('DB_HOST', 'localhost'),
    'port'     => env('DB_PORT', '3306'),
    'user'     => env('DB_USER', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'database' => env('DB_DATABASE', 'phone_book'),
    'cache'    => env('APP_DEBUG', false) ? 'array' : 'filesystem',
];

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'result_cache'    => $db['cache'],
                'metadata_cache'  => $db['cache'],
                'query_cache'     => $db['cache'],
                'hydration_cache' => $db['cache'],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => "{$db['driver']}://{$db['user']}:{$db['password']}@{$db['host']}:{$db['port']}/{$db['database']}"
                ]
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'App\Models' => 'entities',
                ],
            ],
            'entities' => [
                'class' => Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => $db['cache'],
                'paths' => ['app/Models'],
            ],
        ],
        'cache' => [
            'filesystem' => [
                'class' => Doctrine\Common\Cache\FilesystemCache::class,
                'directory' => 'storage/cache/doctrine',
            ],
        ],
    ]
];
