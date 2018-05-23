<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\Write;

class AuraSqlConnectionInterceptor implements MethodInterceptor
{
    const PROP = 'pdo';

    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @var string[]
     */
    private $readsMethods = [];

    /**
     * @var string[]
     */
    private $writeMethods = [];

    /**
     * @Read("readMethods")
     * @Write("writeMethods")
     */
    public function __construct(ConnectionLocatorInterface $connectionLocator, array $readMethods, array $writeMethods)
    {
        $this->connectionLocator = $connectionLocator;
        $this->readsMethods = $readMethods;
        $this->writeMethods = $writeMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $connection = $this->getConnection($invocation);
        $object = $invocation->getThis();
        $ref = new \ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $ref->setValue($object, $connection);

        return $invocation->proceed();
    }

    private function getConnection(MethodInvocation $invocation) : ExtendedPdoInterface
    {
        $methodName = $invocation->getMethod()->name;
        if (\in_array($methodName, $this->readsMethods, true)) {
            return $this->connectionLocator->getRead();
        }

        return $this->connectionLocator->getWrite();
    }
}
