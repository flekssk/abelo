<?php

declare(strict_types=1);

namespace App\Application\Database;

use App\Application\Container\Container;
use App\Application\Container\Contracts\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        $dsn = 'mysql:host=mysql;port=3306;dbname=abelo_test;charset=utf8mb4';
        $username = 'abelo';
        $password = 'password';

        $container->bind(
            DatabaseConnection::class,
            new DatabaseConnection(
                $dsn,
                $username,
                $password
            )
        );
    }
}