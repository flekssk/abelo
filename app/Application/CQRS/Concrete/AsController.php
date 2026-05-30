<?php

namespace App\Application\CQRS\Concrete;

/**
 * @method asController()
 */
trait AsController
{
    public function __invoke(mixed ...$arguments): mixed
    {
        return $this->handle(...$arguments);
    }
}