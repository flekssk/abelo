<?php

declare(strict_types=1);

namespace App\Application\Router;

use Adbar\Dot;
use App\Application\CQRS\Action;
use App\Application\CQRS\ActionDispatchJob;
use App\Application\Request\Enums\RequestMethodEnum;

class Router
{
    public function __construct(
        private Dot $routes
    ) {
        $this->routes = new Dot();
    }

    public function get(string $uri, string $actionClass): void
    {
        $this->add(RequestMethodEnum::GET, $uri, $actionClass);
    }

    public function post(string $uri, string $actionClass): void
    {
        $this->add(RequestMethodEnum::POST, $uri, $actionClass);
    }

    public function add(RequestMethodEnum $requestMethod, string $uri, string $actionClass): void
    {
        if (!is_a($actionClass, Action::class)) {
            throw new \InvalidArgumentException('Callable must be an instance of ' . Action::class);
        }

        $this->routes->add(
            [
                ...explode(
                    '/',
                    trim($uri, ' /')
                ),
                $requestMethod->name,
            ],
            $actionClass
        );
    }

    public function resolveAction(RequestMethodEnum $methodEnum, string $uri): ActionDispatchJob
    {
        $actionParams = [];
        $uriParts = explode('/', trim($uri, ' /'));
        $routes = $this->routes;

        while($uriPart = array_shift($uriParts)) {
            if ($routes->has($uriPart)) {
                $routes = $routes->get($uriPart);
            } else {
                foreach (array_keys($routes) as $arrayKey) {
                    if (preg_match('/\{([^}]+)\}/', $arrayKey, $matches)) {
                        $routeParameterName = $matches[1];
                        $routes = $routes->get($routeParameterName);
                        $actionParams[$routeParameterName] = $uriPart;
                    }
                };
            }
        }

        if ($routes->isEmpty()) {
            throw new \Exception('Route not found', 404);
        }
        if (!$routes->has($methodEnum->name)) {
            throw new \Exception('Method not allowed', 405);
        }

        return new ActionDispatchJob($routes->get($methodEnum->name), $actionParams);
    }
}