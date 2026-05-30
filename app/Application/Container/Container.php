<?php

declare(strict_types=1);

namespace App\Application\Container;

use App\Application\Container\Contracts\ShouldBuildInterface;
use App\Application\Container\Contracts\ShouldCachedInterface;
use App\Application\Container\Contracts\ShouldCompiledInterface;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class Container
{
    private static ?Container $instance = null;
    private array $instances = [];

    public static function getInstance(): Container
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @template TClass of object
     * @param  class-string<TClass>  $className
     * @return TClass
     */
    public function get(string $className): object
    {
        if (isset($this->instances[$className])) {
            if ($this->instances[$className] instanceof Closure) {
                return $this->instances[$className]($this);
            }
            return $this->instances[$className];
        }

        $reflection = $this->buildClassReflection($className);

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return new $className();
        }

        $this->bind($className, $this->build($className, $this->getMethodParameters($constructor)));

        return $this->instances[$className];
    }

    public function bind(string $className, object $instance): void
    {
        $this->instances[$className] = $instance;
    }

    public function build(string $className, $parameters = []): object
    {
        $objects = new $className(...$parameters);

        if ($objects instanceof ShouldBuildInterface) {
            $objects->build();
        }

        return new $className(...$parameters);
    }

    public function buildClassReflection(string $class): ReflectionClass
    {
        if (!class_exists($class)) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        return $reflection;
    }

    public function getMethodParameters(ReflectionMethod $method, array $knownValues = []): array
    {
        $parameters = $method->getParameters();
        $parametersConcrete = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $parametersConcrete[] = $this->get($type->getName());
            } else {
                if (array_key_exists($parameter->getName(), $knownValues)) {
                    $parametersConcrete[$parameter->getName()] = $knownValues[$parameter->getName()];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $parametersConcrete[$parameter->getName()] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cant determine default value for parameter {$parameter->getName()}");
                }
            }
        }

        return $parametersConcrete;
    }
}
