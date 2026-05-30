<?php

declare(strict_types=1);

namespace App\Application\Request;

use App\Application\Request\Enums\RequestMethodEnum;

readonly class Request
{
    public function __construct(
        public RequestMethodEnum $requestMethod,
        public string $uri,
        public array $get,
        public array $post,
        public array $cookies,
        public array $files,
    ) {
    }

    public static function make(): static
    {
        return new static(
            RequestMethodEnum::tryFrom($_SERVER['REQUEST_METHOD']) ?? throw new \Exception(
            'Request Method Not Allowed',
            405
        ),
            $_SERVER['REQUEST_URI'] ?? '',
            $_GET ?? [],
            $_POST ?? [],
            $_COOKIE ?? [],
            $_FILES ?? [],
        );
    }

    public function __get(string $name)
    {
        return $this->get[$name] ?? $this->post[$name] ?? null;
    }
}