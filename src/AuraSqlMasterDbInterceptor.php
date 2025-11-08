<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Override;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use ReflectionProperty;

final readonly class AuraSqlMasterDbInterceptor implements MethodInterceptor
{
    public const string PROP = 'pdo';

    public function __construct(private ConnectionLocatorInterface $connectionLocator)
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
        $connection = $this->connectionLocator->getWrite();
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }
}
