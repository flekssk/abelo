<?php

declare(strict_types=1);

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

// AI generated

function ddump(...$vars) {
    // 1. Получаем место вызова функции ddump()
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
    $callerFile = $backtrace[0]['file'] ?? 'unknown';
    $callerLine = $backtrace[0]['line'] ?? 0;

    // Сделаем красивый относительный путь (вырезаем корень проекта для читаемости)
    $projectRoot = dirname(__DIR__); // Подправьте под вашу структуру
    $shortFile = str_replace($projectRoot, '', $callerFile);

    $cloner = new VarCloner();
    $dumper = new HtmlDumper();

    $output = fopen('php://memory', 'r+');

    // 2. Генерируем стильную шапку с метаданными вызова
    $metaHtml = "
    <div style='background: #2d2d2d; color: #aaaaaa; font-family: monospace; padding: 8px 12px; font-size: 12px; border-bottom: 1px solid #444; border-radius: 4px 4px 0 0; margin-bottom: -5px;'>
        <span style='color: #00ffcc; font-weight: bold;'>⚡ ddump() call:</span> 
        <span style='color: #fff;'>{$shortFile}</span> on line <span style='color: #ffcc00; font-weight: bold;'>{$callerLine}</span>
    </div>
    ";

    fwrite($output, $metaHtml);

    // 3. Рендерим сами дампы переменных
    foreach ($vars as $var) {
        $dumper->dump($cloner->cloneVar($var), $output);
    }

    fseek($output, 0);
    $html = stream_get_contents($output);
    fclose($output);

    // 4. Бросаем ваше кастомное исключение с HTML-кодом
    throw new class($html) extends \RuntimeException {
        private string $html;
        public function __construct(string $html) {
            parent::__construct('Dump die');
            $this->html = $html;
        }
        public function getHtml(): string { return $this->html; }
    };
}