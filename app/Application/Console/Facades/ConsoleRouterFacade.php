<?php

declare(strict_types=1);

namespace App\Application\Console\Facades;

use App\Application\Console\ConsoleRouter;
use App\Application\Container\Facade;

/**
 * @method static void add(string $actionClass)
 * @method static string|null resolveAction(string $commandName)
 */
class ConsoleRouterFacade extends Facade
{
    public static function getFacadeAccessor(): ?string
    {
        return ConsoleRouter::class;
    }
}