<?php

declare(strict_types=1);

namespace App\Application\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private ?PDO $pdo = null;

    public function __construct(
        private readonly string $dsn,
        private readonly string $username,
        private readonly string $password,
        private readonly array $options = []
    ) {
    }

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $this->connect();
        }

        try {
            $this->pdo->query('SELECT 1');
        } catch (PDOException $e) {
            if ($e->getCode() === 'HY000' || str_contains($e->getMessage(), 'gone away')) {
                $this->connect();
            } else {
                throw $e;
            }
        }

        return $this->pdo;
    }

    private function connect(): void
    {
        $defaultOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO(
            $this->dsn,
            $this->username,
            $this->password,
            $this->options + $defaultOptions
        );
    }
}
