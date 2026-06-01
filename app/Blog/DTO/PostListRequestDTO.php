<?php

declare(strict_types=1);

namespace App\Blog\DTO;

readonly class PostListRequestDTO
{
    public function __construct(
        public int $categoryId,
        public ?array $sortBy = [],
        public ?int $page = null,
        public ?int $perPage = null,
    ) {
    }
}