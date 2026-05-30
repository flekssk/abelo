<?php

declare(strict_types=1);

namespace App\Application\Router\Facades;

use App\Application\Container\Facade;
use App\Application\Request\Enums\RequestMethodEnum;
use App\Application\Router\Router;

/**
 * @method static void add(RequestMethodEnum $requestMethod, $uri, string $actionClass)
 * @method static void get(string $uri, string $actionClass)
 * @method static void post(string $uri, string $actionClass)
 */
class RouterFacade extends Facade
{
    public static function getFacadeAccessor(): ?string
    {
        return Router::class;
    }
}