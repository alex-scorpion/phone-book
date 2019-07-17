<?php

namespace Core\Providers;

use Core\Config\Config;
use Psr\Container\ContainerInterface;

class ConfigProvider
{
    public function __invoke(ContainerInterface $container)
    {
        return Config::make(config_path());
    }
}
