<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Override;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

/** @implements ProviderInterface<ExtendedPdo> */

class NamedExtendedPdoProvider implements ProviderInterface, SetContextInterface
{
    private string $context;

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function setContext($context)
    {
        $this->context = $context;
    }

    public function __construct(private readonly InjectorInterface $injector)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): ExtendedPdo
    {
        $connection = $this->injector->getInstance(EnvConnection::class, $this->context);

        return ($connection)();
    }
}
