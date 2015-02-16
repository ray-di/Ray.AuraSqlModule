<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class AuraSqlSlaveDbInterceptor implements MethodInterceptor
{
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
        $connection = $this->connectionLocator->getRead();
        $invocation->getThis()->pdo = $connection;
    }
}
