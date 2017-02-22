<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

class AuraSqlReplicationDbProvider implements ProviderInterface, SetContextInterface
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @param ConnectionLocatorInterface $connectionLocator
     */
    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext($context)
    {
        $this->connectionLocator = $this->injector->getInstance(ConnectionLocatorInterface::class, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $isGetRequest = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET';
        $pdo = $isGetRequest ? $this->connectionLocator->getRead() : $this->connectionLocator->getWrite();

        return $pdo;
    }
}
