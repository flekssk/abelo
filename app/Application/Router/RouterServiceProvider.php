<?php

declare(strict_types=1);

namespace App\Application\Router;

use App\Application\Container\Container;
use App\Application\Container\Contracts\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        $router = $container->build(Router::class);
        $router->build();

        $container->bind(Router::class, $router);
    }
}