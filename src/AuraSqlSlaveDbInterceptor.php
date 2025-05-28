<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Override;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use ReflectionProperty;

class AuraSqlSlaveDbInterceptor implements MethodInterceptor
{
    /**
     * DB property name
     */
    public const PROP = 'pdo';

    public function __construct(private readonly ConnectionLocatorInterface $connectionLocator)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function invoke(MethodInvocation $invocation)
    {
        $object = $invocation->getThis();
        $ref = new ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $connection = $this->connectionLocator->getRead();
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }
}
