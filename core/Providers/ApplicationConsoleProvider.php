<?php

namespace Core\Providers;

use Core\Config\Config;
use Psr\Container\ContainerInterface;
use Core\Console\Application;

class ApplicationConsoleProvider
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(Config::class);
        $app = new Application("{$config['app']['name']} CLI", "1.0", $container);
        $app->loadCommandsConfig($config['console']['commands'])
            ->loadCommandFiles(base_path('core/Console/Commands'), base_path('app/Console/Commands'));

        return $app;
    }
}
