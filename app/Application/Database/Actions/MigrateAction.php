<?php

declare(strict_types=1);

namespace App\Application\Database\Actions;

use App\Application\CQRS\Action;
use App\Application\Database\Migrations\Migration;

class MigrateAction extends Action
{
    public static string $signature = 'migration:migrate';

    public function asCommand(array $args = []): void
    {
        $files = glob(ROOT_DIR . 'database/migrations' . '/*.php');

        if ($files !== false) {
            foreach ($files as $file) {
                if (is_file($file) && is_readable($file)) {
                    $migration = require $file;

                    if (
                        is_a($migration, Migration::class)
                        && method_exists($migration, 'up')
                    ) {
                        echo "Run migration: " . basename($file, '.php') . "\n";
                        try {
                            $migration->up();
                        } catch (\Throwable $e) {
                            echo "Migration failed: " . $e->getMessage() . "\n";
                            die(1);
                        }
                    }
                }
            }
        }
    }
}