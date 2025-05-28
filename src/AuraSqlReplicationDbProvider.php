<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Override;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

/** @implements ProviderInterface<ExtendedPdoInterface> */
class AuraSqlReplicationDbProvider implements ProviderInterface, SetContextInterface
{
    private string $context = '';

    public function __construct(private readonly InjectorInterface $injector)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @param string $context
     */
    #[Override]
    public function setContext($context): void
    {
        $this->context = $context;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): ExtendedPdoInterface
    {
        $connectionLocator = $this->injector->getInstance(ConnectionLocatorInterface::class, $this->context);
        $isGetRequest = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET';

        return $isGetRequest ? $connectionLocator->getRead() : $connectionLocator->getWrite();
    }
}
