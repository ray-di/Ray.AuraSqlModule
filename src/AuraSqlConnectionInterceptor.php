<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\Write;
use ReflectionProperty;

use function in_array;

class AuraSqlConnectionInterceptor implements MethodInterceptor
{
    public const PROP = 'pdo';

    /** @var ConnectionLocatorInterface */
    private $connectionLocator;

    /** @var string[] */
    private $readsMethods = [];

    /**
     * @phpstan-param array<string> $readMethods
     *
     * @Read("readMethods")
     * @Write("writeMethods")
     */
    public function __construct(ConnectionLocatorInterface $connectionLocator, array $readMethods)
    {
        $this->connectionLocator = $connectionLocator;
        $this->readsMethods = $readMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $connection = $this->getConnection($invocation);
        $object = $invocation->getThis();
        $ref = new ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }

    private function getConnection(MethodInvocation $invocation): ExtendedPdoInterface
    {
        $methodName = $invocation->getMethod()->name;
        if (in_array($methodName, $this->readsMethods, true)) {
            return $this->connectionLocator->getRead();
        }

        return $this->connectionLocator->getWrite();
    }
}
