<?php

namespace App\Exceptions;

use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Core\Exceptions\Handler as CoreHandler;

class Handler extends CoreHandler
{
    public function render(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        return parent::render($request, $exception);
    }
}
