<?php

namespace Core\Routing\Exceptions;

use Aura\Router\Exception\RouteNotFound;

class RouteNotFoundException extends RouteNotFound
{
    public function __construct($message = "Router not found", $code = 404)
    {
        parent::__construct($message, $code);
    }
}
