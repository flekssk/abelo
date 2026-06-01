<?php

declare(strict_types=1);

namespace App\Application\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PDOException;

class DatabaseConnection
{
    private ?Connection $connection = null;

    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
    ) {
    }

    public function getConnection(): Connection
    {
        if ($this->connection === null) {
            $this->connect();
        }

        try {
            $this->connection->executeQuery('SELECT 1');
        } catch (PDOException $e) {
            if ($e->getCode() === 'HY000' || str_contains($e->getMessage(), 'gone away')) {
                $this->connect();
            } else {
                throw $e;
            }
        }

        return $this->connection;
    }

    private function connect(): void
    {
        $connectionParams = [
            'dsn' => $this->dsn,
            'user' => $this->username,
            'password' => $this->password,
            'driver' => 'pdo_mysql',
        ];

        $this->connection = DriverManager::getConnection($connectionParams);

    }
}
