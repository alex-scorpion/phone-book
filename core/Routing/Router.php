<?php

namespace Core\Routing;

use Aura\Router\RouterContainer;
use Aura\Router\Route;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequest;

class Router
{
    private $routerContainer;
    private $defaultRoute;

    public function __construct($defaultHandler = null)
    {
        if ($defaultHandler) {
            $this->defaultRoute = (new Route())->handler($defaultHandler);
        }
        $this->routerContainer = new RouterContainer();
    }

    public function loadRoutes(string $routerFile): self
    {
        $routes = $this->routerContainer->getMap();
        if (file_exists($routerFile) && is_file($routerFile)) {
            require "$routerFile";
        } else {
            throw new \InvalidArgumentException("Not found route file {$routerFile}");
        }

        return $this;
    }

    public function match(ServerRequestInterface $request)
    {
        if ($route = $this->getRoute($request)) {
            $handler = $route->handler;

            if (is_callable($handler)) {
                $reflectionHandler = new \ReflectionFunction($handler);
                $handlerArguments = $this->getHandlerArguments($route, $reflectionHandler, $request);
                return $handler(...$handlerArguments);
            }

            if (is_string($handler)) {
                $handlerData = explode('@', $route->handler);
                $handlerClass = "App\Http\Controllers\\" . $handlerData[0] ?? "";
                $handlerAction = $handlerData[1] ?? "";

                if (class_exists($handlerClass)) {
                    if (method_exists($handlerClass, $handlerAction)) {
                        $reflectionController = new \ReflectionClass($handlerClass);
                        $handlerArguments = $this->getHandlerArguments(
                            $route,
                            $reflectionController->getMethod($handlerAction),
                            $request
                        );
                        return (new $handlerClass)->$handlerAction(...$handlerArguments);
                    } else {
                        throw new Exceptions\MethodNotFoundException("Method not found", $handlerClass, $handlerAction);
                    }
                } else {
                    throw new Exceptions\ClassNotFoundException("Controller not found", $handlerClass);
                }
            }
        }
    }

    protected function getRoute(ServerRequestInterface $request): ?Route
    {
        $matcher = $this->routerContainer->getMatcher();
        if ($route = $matcher->match($request)) {
            return $route;
        } elseif (!is_api($request) && $this->defaultRoute) {
            return $this->defaultRoute;
        }

        return null;
    }

    protected function getHandlerArguments(
        Route $route,
        \ReflectionFunctionAbstract $reflectionHandler,
        ServerRequestInterface $request
    ): array {
        $reflectionHandlerArguments = [];

        foreach ($reflectionHandler->getParameters() as $parameter) {
            $parameterName = $parameter->getName();
            if ($parameterClass = $parameter->getClass()) {
                $reflectionHandlerArguments[] = ($parameterClass->getName() === ServerRequest::class)
                    ? $request
                    : app()->container()->get($parameterClass->getName());
            } elseif (array_key_exists($parameterName, $route->attributes)) {
                if ($parameter->hasType()) {
                    $parameterType = $parameter->getType();
                    switch ($parameterType->getName()) {
                        case 'int':
                            $reflectionHandlerArguments[] = (int)$route->attributes[$parameterName];
                            break;
                        case 'string':
                            $reflectionHandlerArguments[] = (string)$route->attributes[$parameterName];
                            break;
                        default:
                            $reflectionHandlerArguments[] = $route->attributes[$parameterName];
                    }
                } else {
                    $reflectionHandlerArguments[] = $route->attributes[$parameterName];
                }
            } elseif ($defaultValue = $parameter->getDefaultValue()) {
                $reflectionHandlerArguments[] = $defaultValue;
            } elseif ($parameter->allowsNull()) {
                $reflectionHandlerArguments[] = null;
            }
        }

        return $reflectionHandlerArguments;
    }
}
