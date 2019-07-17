<?php

namespace Core\Providers;

use Psr\Container\ContainerInterface;
use Core\Exceptions\Handler;

class ErrorHandlerProvider
{
    public function __invoke(ContainerInterface $container)
    {
        return new Handler();
    }
}
