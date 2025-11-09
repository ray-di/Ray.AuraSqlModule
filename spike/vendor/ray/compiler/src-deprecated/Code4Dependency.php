<?php

declare(strict_types=1);

namespace Ray\Compiler;

use Ray\Compiler\Exception\Unbound;
use Ray\Di\Argument;
use Ray\Di\Bind;
use Ray\Di\Container;
use Ray\Di\Dependency;
use Ray\Di\DependencyInterface;
use Ray\Di\NewInstance;
use Ray\Di\SetterMethod;
use Ray\Di\SetterMethods;

use function implode;
use function is_array;
use function sprintf;
use function var_export;

use const PHP_EOL;

/** @deprecated */
final class Code4Dependency extends Code
{
    /** @var Dependency */
    private $dependency;

    /** @var PrivateProperty  */
    private $prop;

    /** @var DependencyInterface[] */
    private $container;

    /** @SuppressWarnings("PHPMD.BooleanArgumentFlag") */
    public function __construct(Container $container, Dependency $dependency, ?IpQualifier $qualifier = null)
    {
        $this->dependency = $dependency;
        $this->isSingleton = $dependency->isSingleton();
        $this->qualifiers = $qualifier;
        $this->prop = new PrivateProperty();
        $this->container = $container->getContainer();
    }

    public function __toString(): string
    {
        $lines = $this->getNewInstanceCode();
        $this->addBindingCode($lines);
        $this->addSetterCode($lines);
        $lines[] = 'return $instance;';

        return implode(PHP_EOL, $lines);
    }

    /** @param array<string> $lines */
    private function addSetterCode(array &$lines): void
    {
        $newInstance = ($this->prop)($this->dependency, 'newInstance');
        // class name
        /** @var class-string $class */
        $class = ($this->prop)($newInstance, 'class');
        /** @var SetterMethods $setterMethodsObject */
        $setterMethodsObject = ($this->prop)($newInstance, 'setterMethods');
        /** @var array<SetterMethod> $setterMethods */
        $setterMethods = (array) ($this->prop)($setterMethodsObject, 'setterMethods');
        foreach ($setterMethods as $setterMethod) {
            $methodName = ($this->prop)($setterMethod, 'method');
            $arguments = ($this->prop)($setterMethod, 'arguments');
            $arguments = ($this->prop)($arguments, 'arguments');
            $args = [];
            foreach ($arguments as $argument) {
                $index = ($this->prop)($argument, 'index');
                $args[] = $this->getArgumentCode($argument, $index);
            }

            if ($args === []) {
                return;
            }

            $argString = implode(', ', $args);
            $lines[] = sprintf('$instance->%s(%s);', $methodName, $argString);
        }
    }

    /** @param array<string> $lines */
    private function addBindingCode(array &$lines): void
    {
        /** @var ?NewInstance */
        $newInstance = ($this->prop)($this->dependency, 'newInstance');
        /** @var ?Bind */
        $bind = ($this->prop)($newInstance, 'bind');
        /** @var ?Bind */
        $aspectBind = ($this->prop)($bind, 'bind');
        /** @var string[][]|null $bindings */
        $bindings = ($this->prop)($aspectBind, 'bindings', null);

        if ($bindings === null) {
            return;
        }

        $line = '$instance->bindings = ' . $this->getBindingsCode($bindings) . ';';
        $lines[] = $line;
    }

    /** @param array<string, array<string>> $bindings */
    private function getBindingsCode(array $bindings): string
    {
        $methodBinding = [];
        foreach ($bindings as $method => $interceptors) {
            $methodBinding[] = sprintf("'%s' => [%s]", $method, $this->getInterceptorCode($interceptors));
        }

        return '[' . implode(', ', $methodBinding) . ']';
    }

    /** @param array<string> $interceptors */
    private function getInterceptorCode(array $interceptors): string
    {
        $interceptorCode = [];
        foreach ($interceptors as $interceptor) {
            $interceptorCode[] = sprintf('$singleton(\'%s-\')', $interceptor);
        }

        return implode(', ', $interceptorCode);
    }

    /** @return array<string> */
    public function getNewInstanceCode(): array
    {
        $newInstance = ($this->prop)($this->dependency, 'newInstance');
        $className = ($this->prop)($newInstance, 'class');

        $arguments = ($this->prop)(($this->prop)($newInstance, 'arguments'), 'arguments');
        $args = [];
        if (is_array($arguments)) {
            foreach ($arguments as $argument) {
                $index = ($this->prop)($argument, 'index');
                $args[] = $this->getArgumentCode($argument, $index);
            }
        }

        $argString = implode(', ', $args);

        return [sprintf("<?php\n\$instance = new %s(%s);", $className, $argString)];
    }

    public function getArgumentCode(Argument $argument, string $index): string
    {
        if (isset($this->container[$index])) {
            return sprintf('$prototype(\'%s\')', $index);
        }

        if ($argument->isDefaultAvailable()) {
            return var_export($argument->getDefaultValue(), true);
        }

        throw new Unbound($index);
    }
}
