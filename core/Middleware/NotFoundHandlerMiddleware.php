<?php

namespace Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class NotFoundHandlerMiddleware implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $httpCode    = 404;
        $httpMessage = 'Not found';

        return is_api($request)
            ? new JsonResponse(['error' => $httpMessage], $httpCode)
            : new HtmlResponse("<h1>{$httpCode} - {$httpMessage}</h1>", $httpCode);
    }
}
