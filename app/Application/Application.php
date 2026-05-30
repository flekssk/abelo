<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Container\Container;
use App\Application\Request\Request;
use App\Application\Router\Router;

class Application
{
    private Container $container;

    public function __construct() {
        $this->container = Container::getInstance();
    }

    public function handleRequest(Request $request): void
    {
        $this->container->bind(Request::class, $request);

        $router = $this->container->get(Router::class);

        $actionJob = $router->resolveAction($request->requestMethod, $request->uri);

        $action = $this->container->get($actionJob->actionClass);

        if (method_exists($action, 'asController')) {
            $action->asController(...$actionJob->parameters);
        }
    }
}