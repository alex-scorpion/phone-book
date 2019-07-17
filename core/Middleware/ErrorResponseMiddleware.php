<?php

namespace Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class ErrorResponseMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 600) {
            $logger = container()->get(LoggerInterface::class);
            $logger->error("{$response->getStatusCode()} - {$response->getReasonPhrase()}", [
                'request'   => [
                    'Method'  => $request->getMethod(),
                    'URL'     => (string)$request->getUri(),
                ],
            ]);
        }

        return $response;
    }
}
