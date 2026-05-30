<?php

declare(strict_types=1);

namespace App\Application\View\Facades;

use App\Application\Container\Facade;
use App\Application\View\ViewFactory;

/**
 * @method static string buildView(string $template, array $data = [])
 */
class ViewFactoryFacade extends Facade
{
    public static function getFacadeAccessor(): ?string
    {
        return ViewFactory::class;
    }
}