<?php

declare(strict_types=1);

namespace App\Blog\DTO;

readonly class PostListDTO
{
    public function __construct(public array $list, public int $totalCount)
    {
    }
}