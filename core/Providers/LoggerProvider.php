<?php

namespace Core\Providers;

use Monolog\Logger;
use Psr\Container\ContainerInterface;

class LoggerProvider
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $logger = new Logger('App');

        foreach ($config['logger']['use'] as $classHandler) {
            if (class_exists($classHandler)) {
                $logger->pushHandler(new $classHandler(...$config['logger']['handlers'][$classHandler]));
            }
        }

        return $logger;
    }
}
