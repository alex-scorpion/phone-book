<?php

namespace Core\Routing\Exceptions;

class NotAcceptableException extends RouteNotFoundException
{
    public function __construct($message = "Not acceptable", $code = 406)
    {
        parent::__construct($message, $code);
    }
}
