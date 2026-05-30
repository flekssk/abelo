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
use App\Application\ErrorHandler\ErrorHandler;
use App\Application\Request\Enums\RequestMethodEnum;
use App\Application\Request\Request;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

const ROOT_DIR = __DIR__ . '/../';

$app = new Application();
$app->serve();

$worker = Worker::create();

$psr17Factory = new Psr17Factory();
$psr7Worker = new PSR7Worker($worker, $psr17Factory, $psr17Factory, $psr17Factory);
$psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

while ($request = $psr7Worker->waitRequest()) {
    try {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();

        $request = new Request(
            RequestMethodEnum::from($request->getMethod()),
            $request->getUri()->getPath(),
            $request->getQueryParams(),
            $request->getParsedBody() ?? [],
            $request->getCookieParams(),
            $request->getUploadedFiles()
        );

        $response = $app->handleRequest($request);

        $psr7Worker->respond($psrHttpFactory->createResponse($response));
    } catch (Throwable $e) {
        $errorResponse = ErrorHandler::render($e);
        $psr7Worker->respond($errorResponse);
    }
}