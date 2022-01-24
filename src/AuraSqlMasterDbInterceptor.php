<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use ReflectionProperty;

class AuraSqlMasterDbInterceptor implements MethodInterceptor
{
    public const PROP = 'pdo';

    private \Aura\Sql\ConnectionLocatorInterface $connectionLocator;

    public function __construct(ConnectionLocatorInterface $connectionLocator)
    {
        $this->connectionLocator = $connectionLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $object = $invocation->getThis();
        $ref = new ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $connection = $this->connectionLocator->getWrite();
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }
}
