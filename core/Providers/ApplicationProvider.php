<?php

namespace Core\Providers;

use Core\Middleware\NotFoundHandlerMiddleware;
use Psr\Container\ContainerInterface;
use Core\Application;
use Zend\Stratigility\MiddlewarePipe;

class ApplicationProvider
{
    public function __invoke(ContainerInterface $container)
    {
        return Application::make($container, $container->get(MiddlewarePipe::class), new NotFoundHandlerMiddleware());
    }
}
