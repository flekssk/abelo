<?php

declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../php_errors.log');

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../bootstrap/helpers.php';

use App\Application\Application;
use App\Application\Database\DatabaseServiceProvider;
use App\Application\Router\RouterServiceProvider;

const ROOT_DIR = __DIR__ . '/../';

$app = new Application([
    DatabaseServiceProvider::class,
    RouterServiceProvider::class
]);

$app->serve();