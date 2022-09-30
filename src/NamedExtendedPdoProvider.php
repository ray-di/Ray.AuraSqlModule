<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

use function assert;

/**
 * @implements ProviderInterface<ExtendedPdo>
 */

class NamedExtendedPdoProvider implements ProviderInterface, SetContextInterface
{
    private InjectorInterface $injector;
    private string $context;

    /**
     * {@inheritDoc}
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): ExtendedPdo
    {
        $connection = $this->injector->getInstance(EnvConnection::class, $this->context);
        assert($connection instanceof EnvConnection);

        return ($connection)();
    }
}
