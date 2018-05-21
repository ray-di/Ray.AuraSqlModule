<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class AuraSqlMasterDbInterceptor implements MethodInterceptor
{
    const PROP = 'pdo';

    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @param ConnectionLocatorInterface $connectionLocator
     */
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
        $ref = new \ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $connection = $this->connectionLocator->getWrite();
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }
}
