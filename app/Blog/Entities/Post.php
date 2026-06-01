<?php

declare(strict_types=1);

namespace App\Blog\Entities;

class Post
{
    public function __construct(
        int $id,
        string $name,
        string $description,
        string $createdAt
    ) {
    }
}