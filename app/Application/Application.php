<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Console\ConsoleRouter;
use App\Application\Console\Facades\ConsoleRouterFacade;
use App\Application\Container\Container;
use App\Application\Container\Contracts\ServiceProviderInterface;
use App\Application\Request\Enums\ContentTypeEnum;
use App\Application\Request\Request;
use App\Application\Router\Router;
use App\Application\ErrorHandler\ErrorHandler;
use App\Application\Request\Enums\RequestMethodEnum;
use Exception;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Throwable;

class Application
{
    private Container $container;

    public function __construct(
        private readonly array $serviceProviders = []
    ) {
        $this->container = Container::getInstance();
    }

    public function handleRequest(Request $request)
    {
        $router = $this->container->get(Router::class);
        $actionJob = $router->resolveAction($request->requestMethod, $request->uri);
        $action = $this->container->get($actionJob->actionClass);
        $actionMethod = match ($request->contentType) {
            ContentTypeEnum::JSON => 'asJson',
            ContentTypeEnum::TEXT_HTML => 'asHtml',
            default => null,
        };

        if ($actionMethod === null) {
            throw new \Exception('This application cant work with ' . $request->contentType->value);
        }

        if (!method_exists($action, $actionMethod)) {
            throw new Exception("Action does not have a method $actionMethod");
        }

        return $action->asHtml(...array_merge($actionJob->params, ['request' => $request]));
    }

    public function serve(): void
    {
        $this->registerProviders();
        $this->container->build(Router::class);

        $worker = Worker::create();

        $psr17Factory = new Psr17Factory();
        $psr7Worker = new PSR7Worker($worker, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        while ($request = $psr7Worker->waitRequest()) {
            try {
                $request = new Request(
                    RequestMethodEnum::from($request->getMethod()),
                    $request->getUri()->getPath(),
                    $request->getQueryParams(),
                    $request->getParsedBody() ?? [],
                    $request->getCookieParams(),
                    $request->getUploadedFiles(),
                    ContentTypeEnum::fromHeaderLine(
                        $request->getHeaderLine('Content-Type')
                    ) ?? ContentTypeEnum::TEXT_HTML
                );

                $response = $this->handleRequest($request);

                $psr7Worker->respond($psrHttpFactory->createResponse($response));
            } catch (Throwable $e) {
                $errorResponse = ErrorHandler::render($e);
                $psr7Worker->respond($errorResponse);
            }
        }
    }

    public function runConsole(array $argv): void
    {
        $this->registerProviders();
        $this->container->build(ConsoleRouter::class);
        $commandName = $argv[1] ?? null;

        if ($commandName === null) {
            echo "Command name not specified\n";
            die(1);
        }

        $commandAction = ConsoleRouterFacade::resolveAction($commandName);

        if ($commandAction === null) {
            echo "Command action not specified\n";
            die(1);
        }

        echo "Run command $commandName\n";

        $action = $this->container->build($commandAction);

        $action->asCommand($argv);

        die(0);
    }

    public function registerProviders(): void
    {
        /** @var class-string<ServiceProviderInterface> $serviceProviderClass */
        foreach ($this->serviceProviders as $serviceProviderClass) {
            $serviceProvider = new $serviceProviderClass();
            $serviceProvider->register($this->container);
        }
    }
}