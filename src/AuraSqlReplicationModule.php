<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlReplicationModule extends AbstractModule
{
    private ConnectionLocatorInterface $connectionLocator;
    private string $qualifer;

    public function __construct(
        ConnectionLocatorInterface $connectionLocator,
        string $qualifer = '',
        ?AbstractModule $module = null
    ) {
        $this->connectionLocator = $connectionLocator;
        $this->qualifer = $qualifer;
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind(ConnectionLocatorInterface::class)->annotatedWith($this->qualifer)->toInstance($this->connectionLocator);
        // ReadOnlyConnection when GET, otherwise WriteConnection
        $this->bind(ExtendedPdoInterface::class)->annotatedWith($this->qualifer)->toProvider(AuraSqlReplicationDbProvider::class, $this->qualifer)->in(Scope::SINGLETON);
        // @ReadOnlyConnection @WriteConnection
        $this->installReadWriteConnection();
        // @Transactional
        $this->install(new TransactionalModule());
    }

    protected function installReadWriteConnection(): void
    {
        // @ReadOnlyConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(ReadOnlyConnection::class),
            [AuraSqlSlaveDbInterceptor::class]
        );
        // @WriteConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(WriteConnection::class),
            [AuraSqlMasterDbInterceptor::class]
        );
    }
}
