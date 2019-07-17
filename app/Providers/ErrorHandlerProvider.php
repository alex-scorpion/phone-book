<?php

namespace App\Providers;

use Psr\Container\ContainerInterface;
use App\Exceptions\Handler;

class ErrorHandlerProvider
{
    public function __invoke(ContainerInterface $container)
    {
        return new Handler();
    }
}
