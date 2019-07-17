<?php

namespace Core\Providers;

use Core\Config\Config;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;

class MiddlewareProvider
{
    public function __invoke(ContainerInterface $container)
    {
        $pipeline = new MiddlewarePipe();

        foreach ($container->get(Config::class)['middleware'] as $middleware) {
            if (class_exists($middleware)) {
                $middleware = new $middleware();
                if ($middleware instanceof MiddlewareInterface) {
                    $pipeline->pipe(new $middleware);
                }
            }
        }

        return $pipeline;
    }
}
