<?php

declare(strict_types=1);

namespace App\Application\Container;

use App\Application\Router\Router;

class Facade
{
    public static function getFacadeAccessor(): ?string
    {
        return null;
    }

    public static function getFacadeInstance()
    {
        if (static::getFacadeAccessor() === null) {
            throw new \BadMethodCallException('No facade instance has been set.');
        }

        return Container::getInstance()->get(Router::class);
    }

    public static function __callStatic(string $name, array $arguments): mixed
    {
        return static::getFacadeInstance()->$name(...$arguments);
    }
}