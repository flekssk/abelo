<?php

declare(strict_types=1);

use App\Application\Database\Facades\Db;
use App\Application\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Db::getConnection()
            ->executeQuery(
                $sql = "
                    CREATE TABLE IF NOT EXISTS `migrations` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `migration` VARCHAR(255) NOT NULL,
                        `batch` INT NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
            );
    }

    public function down(): void
    {
        Db::getConnection()->executeQuery("DROP TABLE IF EXISTS `migrations`;");
    }
};