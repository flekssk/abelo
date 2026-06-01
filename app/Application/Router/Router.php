<?php

declare(strict_types=1);

namespace App\Application\Router;

use App\Application\Container\Contracts\ShouldBuildInterface;
use App\Application\CQRS\Action;
use App\Application\CQRS\ActionDispatchJob;
use App\Application\Request\Enums\RequestMethodEnum;
use Illuminate\Support\Arr;

class Router implements ShouldBuildInterface
{
    private bool $built = false;
    private array $routes = [];

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
        if (!is_a($actionClass, Action::class, true)) {
            throw new \InvalidArgumentException('Callable must be an instance of ' . Action::class);
        }
        
        Arr::set(
            $this->routes,
            str_replace('/', '.', $uri) . '.' . $requestMethod->name,
            $actionClass
        );
    }

    public function resolveAction(RequestMethodEnum $methodEnum, string $uri): ActionDispatchJob
    {
        if (!$this->built) {
            $this->build();
        }

        $actionParams = [];
        $uriParts = explode('/', trim($uri, ' /'));
        $routes = $this->routes;
        ddump($this->routes);

        while(($uriPart = array_shift($uriParts)) !== null) {
            if (array_key_exists($uriPart, $routes)) {
                $routes = $routes->get($uriPart);
            } else {
                foreach ($routes->keys() as $arrayKey) {
                    if (preg_match('/\{([^}]+)\}/', $arrayKey, $matches)) {
                        $routeParameterName = $matches[1];
                        $routes = $routes[$routeParameterName];
                        $actionParams[$routeParameterName] = $uriPart;
                    }
                };
            }
        }

        if (!is_array($routes)) {
            throw new \Exception('Route not found', 404);
        }
        if (!array_key_exists($methodEnum->name, $routes)) {
            throw new \Exception('Method not allowed', 405);
        }

        return new ActionDispatchJob($routes[$methodEnum->name], $actionParams);
    }

    public function build(): void
    {
        $file = ROOT_DIR . 'routes/api.php';
        if (is_file($file) && is_readable($file)) {
            include_once $file;
        }
        $file = ROOT_DIR . 'routes/web.php';
        if (is_file($file) && is_readable($file)) {
            include_once $file;
        }

        $this->built = true;
    }
}