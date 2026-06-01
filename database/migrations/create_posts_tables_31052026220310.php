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
                "CREATE TABLE `categories` (
                    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(255) NOT NULL,
                    `description` TEXT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB;"
            );

        Db::getConnection()
            ->executeQuery(
                "
                    CREATE TABLE `posts` (
                        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        `title` VARCHAR(255) NOT NULL,
                        `image` VARCHAR(255) NULL COMMENT 'Путь к файлу изображения',
                        `description` TEXT NOT NULL COMMENT 'Краткое описание для превью',
                        `content` LONGTEXT NOT NULL COMMENT 'Полный текст статьи',
                        `views_count` INT UNSIGNED DEFAULT 0,
                        `published_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        
                        INDEX `idx_views` (`views_count`),
                        INDEX `idx_published` (`published_at`)
                    ) ENGINE=InnoDB;"
            );

        Db::getConnection()
            ->executeQuery(
                "
                    CREATE TABLE `post_category` (
                        `post_id` INT UNSIGNED NOT NULL,
                        `category_id` INT UNSIGNED NOT NULL,
                        PRIMARY KEY (`post_id`, `category_id`),
                        CONSTRAINT `fk_pc_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
                        CONSTRAINT `fk_pc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB;"
            );
    }

    public function down(): void
    {
    }
};