<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\PdoInterface;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

use function assert;

class AuraSqlReplicationDbProvider implements ProviderInterface, SetContextInterface
{
    /** @var InjectorInterface */
    private $injector;

    /** @var string */
    private $context = '';

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $context
     */
    public function setContext($context): void
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     *
     * @return PdoInterface
     */
    public function get()
    {
        $connectionLocator = $this->injector->getInstance(ConnectionLocatorInterface::class, $this->context);
        assert($connectionLocator instanceof ConnectionLocatorInterface);
        $isGetRequest = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET';

        return $isGetRequest ? $connectionLocator->getRead() : $connectionLocator->getWrite();
    }
}
