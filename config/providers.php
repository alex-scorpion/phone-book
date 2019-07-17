<?php

use Core\Providers as CoreProvider;
use App\Providers as AppProvider;

return [
    'dependencies' => [
        'factories' => [
            /**
             * Core providers
             */
            Core\Application::class                    => CoreProvider\ApplicationProvider::class,
            Core\Console\Application::class            => CoreProvider\ApplicationConsoleProvider::class,
            Core\Config\Config::class                  => CoreProvider\ConfigProvider::class,
            Core\Routing\Router::class                 => CoreProvider\RouterProvider::class,
            Zend\Stratigility\MiddlewarePipe::class    => CoreProvider\MiddlewareProvider::class,
            Psr\Log\LoggerInterface::class             => CoreProvider\LoggerProvider::class,
            //Core\Exceptions\Handler::class             => CoreProvider\ErrorHandlerProvider::class,
            Doctrine\ORM\EntityManagerInterface::class => ContainerInteropDoctrine\EntityManagerFactory::class,
            Doctrine\Migrations\Tools\Console\Command\DiffCommand::class => CoreProvider\DoctrineDiffCommandProvider::class,

            /**
             * App providers
             */
            Core\Exceptions\Handler::class             => AppProvider\ErrorHandlerProvider::class,
        ],
        'aliases' => [
            'config' => Core\Config\Config::class,
        ]
    ]
];