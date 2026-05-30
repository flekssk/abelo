<?php

declare(strict_types=1);

namespace App\Application\Container\Contracts;

interface ShouldBuildInterface
{
    public function build(): void;
}