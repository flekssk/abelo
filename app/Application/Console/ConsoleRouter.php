<?php

declare(ticks=1);

namespace App\Application\Console;

use App\Application\Container\Contracts\ShouldBuildInterface;
use App\Application\CQRS\Action;
use Illuminate\Support\Collection;

class ConsoleRouter implements ShouldBuildInterface
{
    /**
     * @var Collection<string, class-string<Action>>
     */
    private Collection $routes;

    public function __construct() {
        $this->routes = collect();
    }

    public function add(string $actionClass): void
    {
        if (!is_a($actionClass, Action::class, true)) {
            throw new \Exception("Action class must be an instance of " . Action::class);
        }

        if (!method_exists($actionClass, 'asCommand')) {
            throw new \Exception('Action class must use the asCommand method');
        }

        if (empty($actionClass::$signature)) {
            throw new \Exception('Action class must use the signature method');
        }

        $this->routes[$actionClass::$signature] = $actionClass;
    }

    public function resolveAction(string $commandName): ?string
    {
        return $this->routes->get($commandName);
    }

    public function build(): void
    {
        $file = ROOT_DIR . 'routes/console.php';

        if (is_file($file) && is_readable($file)) {
            include_once $file;
        }
    }
}