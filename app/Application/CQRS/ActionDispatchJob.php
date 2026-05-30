<?php

declare(strict_types=1);

namespace App\Application\CQRS;

readonly class ActionDispatchJob
{
    /**
     * @param class-string<Action> $actionClass
     */
    public function __construct(
        public string $actionClass,
        public array $params = [],
    ) {
    }
}