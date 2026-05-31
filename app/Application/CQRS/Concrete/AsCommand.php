<?php

declare(strict_types=1);

namespace App\Application\CQRS\Concrete;

trait AsCommand
{
    public static string $signature;

    public function asCommand(array $args = [])
    {
    }
}