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
     */
    public function matchesClass(\ReflectionClass $class, array $arguments)
    {
        unset($class, $arguments);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method, array $arguments)
    {
        $result = \in_array($method->name, $arguments[0], true);

        return $result;
    }
}
