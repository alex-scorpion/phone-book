<?php

use Dotenv\Dotenv;
use Core\Application;
use Core\Config\Config;
use Zend\ServiceManager\ServiceManager;

Dotenv::create(base_path())->load();

$config = Config::make(config_path());
$container = new ServiceManager($config['dependencies']);

if ($config['app']['debug'] ?? false()) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
}

return $container->get(Application::class);