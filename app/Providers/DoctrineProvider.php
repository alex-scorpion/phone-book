<?php

namespace App\Providers;

use ContainerInteropDoctrine\ConfigurationFactory;
use ContainerInteropDoctrine\ConnectionFactory;
use ContainerInteropDoctrine\EntityManagerFactory;
use Core\Support\Config;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class DoctrineProvider extends EntityManagerFactory
{
    private $configKey;

    public function __construct($configKey = 'orm_default')
    {
        $this->configKey = $configKey;
        parent::__construct($configKey);
    }

    protected function createWithConfig(ContainerInterface $container, $configKey)
    {
        $config = $container->get(Config::class);

        return EntityManager::create(
            $this->retrieveDependency(
                $container,
                $config->get('database'),
                'connection',
                ConnectionFactory::class
            ),
            $this->retrieveDependency(
                $container,
                $config->get('database'),
                'configuration',
                ConfigurationFactory::class
            )
        );
    }

    protected function getDefaultConfig($configKey)
    {
        return [
            'connection'    => 'connection',
            'configuration' => 'configuration',
        ];
    }
}
