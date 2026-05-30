<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use App\Application\Application;
use App\Application\Request\Request;

$app = new Application();

$app->handleRequest(Request::make());