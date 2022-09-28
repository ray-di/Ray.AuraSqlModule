<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

/**
 * @implements ProviderInterface<ConnectionLocatorInterface>
 */
final class ConnectionLocatorProvider implements ProviderInterface, SetContextInterface
{
    /** @var array<string> */
    private array $dsn;

    /** @var array<string> */
    private array $user;

    /** @var array<string> */
    private array $password;

    /** @var array<string> */
    private array $slave;
    private string $context;

    /**
     * {@inheritDoc}
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @param array<string> $dsn
     * @param array<string> $user
     * @param array<string> $password
     * @param array<string> $slave
     *
     * @Named("dsn=pdo_locator_dsn,user=pdo_locator_user,password=pdo_locator_pass,slave=pdo_locator_slave")
     */
    #[Named('dsn=pdo_locator_dsn,user=pdo_locator_user,password=pdo_locator_pass,slave=pdo_locator_slave')]
    public function __construct(array $dsn, array $user, array $password, array $slave)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->slave = $slave;
    }

    public function get(): ConnectionLocatorInterface
    {
        return ConnectionLocatorFactory::newInstance(
            $this->dsn[$this->context],
            $this->user[$this->context],
            $this->password[$this->context],
            $this->slave[$this->context]
        );
    }
}
