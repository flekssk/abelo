<?php

declare(strict_types=1);

namespace App\Application\ErrorHandler;

use ErrorException;
use Throwable;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

/**
 * AI generated
 */
class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler(static function (int $errno, string $errstr, string $errfile, int $errline) {
            if (!(error_reporting() & $errno)) {
                return false;
            }
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
    }

    public static function render(Throwable $exception): ResponseInterface
    {
        $factory = new Psr17Factory();

        if (is_a($exception, \RuntimeException::class) && method_exists($exception, 'getHtml')) {
            $factory = new Psr17Factory();
            $response = $factory->createResponse(200)->withHeader('Content-Type', 'text/html; charset=utf-8');
            $response->getBody()->write($exception->getHtml());
            return $response;
        }

        $html = self::generateDebugHtml($exception);

        $response = $factory->createResponse(500)
            ->withHeader('Content-Type', 'text/html; charset=utf-8');

        $response->getBody()->write($html);

        return $response;
    }

    private static function generateDebugHtml(Throwable $e): string
    {
        $htmlMessage = htmlspecialchars($e->getMessage());
        $class = get_class($e);
        $file = $e->getFile();
        $line = $e->getLine();

        // Читаем кусок кода, где произошла ошибка
        $codeSnippet = self::getCodeSnippet($file, $line);
        $trace = nl2br(htmlspecialchars($e->getTraceAsString()));

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Internal Server Error</title>
            <style>
                body { font-family: 'SF Pro Display', -apple-system, sans-serif; background: #1a1a2e; color: #e6e6e6; padding: 40px; margin: 0; line-height: 1.6; }
                .container { max-width: 1000px; margin: 0 auto; background: #161224; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); padding: 30px; border-left: 5px solid #ff4a5a; }
                h1 { color: #ff4a5a; margin-top: 0; font-size: 28px; font-weight: 600; }
                .meta { font-size: 14px; color: #a0a0b8; margin-bottom: 20px; background: #211b36; padding: 10px 15px; border-radius: 6px; }
                .meta strong { color: #fff; }
                pre { background: #0f0c1b; padding: 15px; border-radius: 8px; overflow-x: auto; font-family: 'Fira Code', monospace; font-size: 14px; color: #00ffcc; border: 1px solid #2d254d; }
                .snippet { background: #0f0c1b; font-family: 'Fira Code', monospace; font-size: 14px; border-radius: 8px; padding: 10px; border: 1px solid #2d254d; margin-bottom: 20px; }
                .snippet-line { display: flex; }
                .line-num { width: 40px; color: #5c4d8c; text-align: right; padding-right: 15px; user-select: none; }
                .line-code { white-space: pre-wrap; color: #a0a0b8; }
                .line-error { background: rgba(255, 74, 90, 0.15); width: 100%; display: inline-block; color: #ff4a5a; font-weight: bold; }
                .line-error .line-code { color: #ff7582; }
                h2 { font-size: 18px; color: #ffbc00; margin-top: 30px; border-bottom: 1px solid #2d254d; padding-bottom: 8px; }
                .trace { color: #9c9cb4; font-size: 13px; font-family: 'Fira Code', monospace; background: #0f0c1b; padding: 15px; border-radius: 8px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>{$class}</h1>
                <div style='font-size: 20px; font-weight: bold; margin-bottom: 15px;'>{$htmlMessage}</div>
                <div class='meta'>В файле: <strong>{$file}</strong> на строке <strong>{$line}</strong></div>
                
                <h2>Фрагмент кода:</h2>
                <div class='snippet'>{$codeSnippet}</div>

                <h2>Стек вызовов (Stack Trace):</h2>
                <div class='trace'>{$trace}</div>
            </div>
        </body>
        </html>
        ";
    }

    private static function getCodeSnippet(string $file, int $line): string
    {
        if (!is_file($file) || !is_readable($file)) {
            return "Код недоступен";
        }

        $lines = file($file);
        $start = max(0, $line - 5);
        $end = min(count($lines), $line + 5);

        $snippet = "";
        for ($i = $start; $i < $end; $i++) {
            $currentLineNum = $i + 1;
            $isErrorLine = ($currentLineNum === $line);
            $lineContent = htmlspecialchars($lines[$i]);

            $cssClass = $isErrorLine ? 'snippet-line line-error' : 'snippet-line';

            $snippet .= "<div class='{$cssClass}'>"
                . "<span class='line-num'>{$currentLineNum}</span>"
                . "<span class='line-code'>{$lineContent}</span>"
                . "</div>";
        }
        return $snippet;
    }
}
