<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\Aop\AbstractMatcher;
use ReflectionClass;
use ReflectionMethod;

use function in_array;

class IsInMethodMatcher extends AbstractMatcher
{
    /**
     * {@inheritdoc}
     *
     * @phpstan-param ReflectionClass<object> $class
     * @phpstan-param array<mixed> $arguments
     *
     * @codeCoverageIgnore
     */
    public function matchesClass(ReflectionClass $class, array $arguments): bool
    {
        unset($class, $arguments);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-param array<mixed> $arguments
     */
    public function matchesMethod(ReflectionMethod $method, array $arguments): bool
    {
        return in_array($method->name, $arguments[0], true);
    }
}
