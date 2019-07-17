<?php

/**
 * Подключение SymfonyConsoleCommand из composer пакетов
 */

return [
    'console' => [
        'commands' => [
            Doctrine\Migrations\Tools\Console\Command\ExecuteCommand::class,
            Doctrine\Migrations\Tools\Console\Command\GenerateCommand::class,
            Doctrine\Migrations\Tools\Console\Command\LatestCommand::class,
            Doctrine\Migrations\Tools\Console\Command\MigrateCommand::class,
            Doctrine\Migrations\Tools\Console\Command\DiffCommand::class,
            Doctrine\Migrations\Tools\Console\Command\UpToDateCommand::class,
            Doctrine\Migrations\Tools\Console\Command\StatusCommand::class,
            Doctrine\Migrations\Tools\Console\Command\VersionCommand::class,
        ]
    ]
];