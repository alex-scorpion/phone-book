<?php

namespace Core\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;

class Application extends \Symfony\Component\Console\Application
{
    protected $container;

    public function __construct(string $name, string $version, ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($name, $version);
    }

    public function loadCommandFiles(string $coreCommandsPath = '', string $appCommandsPath = ''): self
    {
        if ($coreCommandsPath) {
            $this->loadCommandsDir($coreCommandsPath, 'Core');
        }

        if ($appCommandsPath) {
            $this->loadCommandsDir($appCommandsPath, 'App');
        }

        return $this;
    }

    public function loadCommandsConfig(array $commands = []): self
    {
        foreach ($commands as $command) {
            if (class_exists($command)) {
                $command = new $command;
                if ($command instanceof Command) {
                    $this->add($command);
                }
            }
        }

        return $this;
    }

    private function loadCommandsDir(string $dirCommands, string $namespace = '')
    {
        if ($dirCommands && file_exists($dirCommands) && is_dir($dirCommands)) {
            $mask = "{$dirCommands}/*.php";
            foreach (glob("{$dirCommands}/*.php") as $commandFile) {
                $nameFile = str_replace(explode("*", $mask), "", $commandFile);
                $classCommand = "{$namespace}\Console\Commands\\{$nameFile}";
                if (class_exists($classCommand)) {
                    $classCommand = new $classCommand;
                    if ($classCommand instanceof Command) {
                        $this->add($classCommand);
                    }
                }
            }
        }
    }
}
