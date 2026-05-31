<?php

declare(strict_types=1);

namespace App\Application\Request;

use App\Application\Request\Enums\ContentTypeEnum;
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
        public ContentTypeEnum $contentType,
    ) {
    }

    public function __get(string $name)
    {
        return $this->get[$name] ?? $this->post[$name] ?? null;
    }
}