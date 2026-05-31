<?php

declare(strict_types=1);

namespace App\Application\Request\Enums;

/**
 * AI generated
 */
enum ContentTypeEnum: string
{
    case JSON = 'application/json';    case FORM_URLENCODED = 'application/x-www-form-urlencoded';
    case MULTIPART_FORM = 'multipart/form-data';
    case XML = 'application/xml';
    case TEXT_XML = 'text/xml';
    case TEXT_HTML = 'text/html';
    case TEXT_PLAIN = 'text/plain';

    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case SVG = 'image/svg+xml';
    case PDF = 'application/pdf';
    case ZIP = 'application/zip';

    public static function fromHeaderLine(string $headerLine): ?self
    {
        $cleanType = explode(';', $headerLine)[0];

        $cleanType = strtolower(trim($cleanType));

        return self::tryFrom($cleanType);
    }
}
