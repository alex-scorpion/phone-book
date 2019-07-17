<?php

namespace Core\Exceptions;

use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class Handler
{
    public function logger(ServerRequestInterface $request, Throwable $exception): self
    {
        if (container()->has(LoggerInterface::class)) {
            $requestArr = self::requestToArray($request);
            $logger = container()->get(LoggerInterface::class);
            $logger->error($exception->getMessage(), [
                'exception' => $exception,
                'request'   => [
                    'HTTP Method'  => $requestArr['HTTP Method'],
                    'URI'          => $requestArr['URI'],
                ],
            ]);
        }
        return $this;
    }

    public function render(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        return is_api($request)
            ? $this->renderJson($request, $exception)
            : $this->renderHtml($request, $exception);
    }

    protected function getHttpStatusCode(Throwable $exception): int
    {
        $code = (int)$exception->getCode();
        return ($code >= 400 && $code < 600) ? $code : 500;
    }

    protected static function isDebug(): bool
    {
        return (bool)config('app.debug', false);
    }

    protected static function requestToArray(ServerRequestInterface $request): array
    {
        return [
            'HTTP Method'  => $request->getMethod(),
            'URI'          => (string)$request->getUri(),
            'Script'       => $request->getServerParams()['SCRIPT_NAME'],
            'Headers'      => $request->getHeaders(),
            'Cookies'      => $request->getCookieParams(),
            'Attributes'   => $request->getAttributes(),
            'Query Params' => $request->getQueryParams(),
            'Body Params'  => $request->getParsedBody(),
        ];
    }

    private function newWhoops(\Whoops\Handler\HandlerInterface $handler): \Whoops\Run
    {
        $whoops = new \Whoops\Run;
        $whoops->writeToOutput(false);
        $whoops->allowQuit(false);
        $whoops->appendHandler($handler);
        $whoops->register();

        return $whoops;
    }

    private function renderHtml(ServerRequestInterface $request, Throwable $exception): HtmlResponse
    {
        $responseCode = self::getHttpStatusCode($exception);

        if (self::isDebug()) {
            $prettyPageHandler = new \Whoops\Handler\PrettyPageHandler();
            $prettyPageHandler->addDataTable('Application request', self::requestToArray($request));

            return new HtmlResponse($this->newWhoops($prettyPageHandler)->handleException($exception), $responseCode);
        } else {
            return new HtmlResponse("<h1>{$responseCode} - Server error.</h1>", $responseCode);
        }
    }

    private function renderJson(ServerRequestInterface $request, Throwable $exception): JsonResponse
    {
        $responseCode = self::getHttpStatusCode($exception);

        if (self::isDebug()) {
            $jsonResponseHandler = new \Whoops\Handler\JsonResponseHandler();
            $jsonResponseHandler->addTraceToOutput(true);
            $data = json_decode($this->newWhoops($jsonResponseHandler)->handleException($exception), true);
            return new JsonResponse($data, $responseCode);
        } else {
            return new JsonResponse(["error" => "server error"], $responseCode);
        }
    }
}
