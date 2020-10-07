<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Ray\Aop\AbstractMatcher;

class IsInMethodMatcher extends AbstractMatcher
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     * @phpstan-param \ReflectionClass<object> $class
     * @phpstan-param array<mixed> $arguments
     */
    public function matchesClass(\ReflectionClass $class, array $arguments) : bool
    {
        unset($class, $arguments);

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-param array<mixed> $arguments
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments) : bool
    {
        $result = \in_array($method->name, $arguments[0], true);

        return $result;
    }
}
