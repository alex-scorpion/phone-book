<?php

namespace Core\Routing\Exceptions;

use Throwable;

class ErrorParseHandlerException extends \ReflectionException
{
    public function __construct($message = "Error parse arguments for route", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
