<?php

namespace Core\Providers;

use Core\Routing\Router;
use Core\Config\Config;
use Psr\Container\ContainerInterface;

class RouterProvider
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(Config::class);
        $router = new Router($config['router']['default-web-handler']);

        $route_path = $config['router']['path'];
        foreach ($config['router']['files'] as $route_file) {
            $router->loadRoutes("{$route_path}/{$route_file}.php");
        }

        return $router;
    }
}
