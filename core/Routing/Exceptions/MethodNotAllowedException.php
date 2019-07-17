<?php

namespace Core\Routing\Exceptions;

class MethodNotAllowedException extends RouteNotFoundException
{
    public function __construct($message = "Method not allowed", $code = 405)
    {
        parent::__construct($message, $code);
    }
}
