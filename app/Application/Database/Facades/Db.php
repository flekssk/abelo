<?php

declare(strict_types=1);

namespace App\Application\Database\Facades;

use App\Application\Container\Facade;
use App\Application\Database\DatabaseConnection;
use Doctrine\DBAL\Connection;

/**
 * @method static Connection getConnection()
 */
class Db extends Facade
{
    public static function getFacadeAccessor(): ?string
    {
        return DatabaseConnection::class;
    }
}