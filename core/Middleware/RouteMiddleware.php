<?php

namespace Core\Middleware;

use Core\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($result = container()->get(Router::class)->match($request)) {
            if ($result instanceof ResponseInterface) {
                return $result;
            }
        }

        return $handler->handle($request);
    }
}
