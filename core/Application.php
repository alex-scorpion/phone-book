<?php

namespace Core;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    private static $obj_instance;
    /** @var ContainerInterface $container */
    private $container;
    /** @var MiddlewareInterface $middleware */
    private $middleware;
    /** @var RequestHandlerInterface $defaultHandler */
    private $defaultHandler;

    public static function make(
        ContainerInterface $container,
        MiddlewareInterface $middleware,
        RequestHandlerInterface $defaultHandler
    ): self {
        if (self::$obj_instance === null) {
            self::$obj_instance = new self;
            self::$obj_instance->container      = $container;
            self::$obj_instance->middleware     = $middleware;
            self::$obj_instance->defaultHandler = $defaultHandler;
        }

        return self::$obj_instance;
    }

    public static function getInstance(): ?self
    {
        return self::$obj_instance;
    }

    public function process(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->defaultHandler);
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    private function __construct()
    {
    }
    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
}
