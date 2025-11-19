<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Override;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class AuraSqlReplicationModule extends AbstractModule
{
    public function __construct(
        private readonly ConnectionLocatorInterface $connectionLocator,
        private readonly string $qualifer = '',
        ?AbstractModule $module = null
    ) {
        parent::__construct($module);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        if ($this->qualifer === '') {
            $this->configureWithoutQualifier();
        } else {
            $this->configureWithQualifier();
        }

        // @ReadOnlyConnection @WriteConnection
        $this->installReadWriteConnection();
    }

    private function configureWithoutQualifier(): void
    {
        $this->bind(ConnectionLocatorInterface::class)
            ->toInstance($this->connectionLocator);

        // ReadOnlyConnection when GET, otherwise WriteConnection
        $this->bind(ExtendedPdoInterface::class)
            ->toProvider(AuraSqlReplicationDbProvider::class, '')
            ->in(Scope::SINGLETON);
    }

    private function configureWithQualifier(): void
    {
        $this->bind(ConnectionLocatorInterface::class)
            /** @phpstan-ignore argument.type */
            ->annotatedWith($this->qualifer)
            ->toInstance($this->connectionLocator);

        // ReadOnlyConnection when GET, otherwise WriteConnection
        $this->bind(ExtendedPdoInterface::class)
            /** @phpstan-ignore argument.type */
            ->annotatedWith($this->qualifer)
            ->toProvider(AuraSqlReplicationDbProvider::class, $this->qualifer)
            ->in(Scope::SINGLETON);
    }

    protected function installReadWriteConnection(): void
    {
        // @ReadOnlyConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(ReadOnlyConnection::class),
            [AuraSqlSlaveDbInterceptor::class],
        );
        // @WriteConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(WriteConnection::class),
            [AuraSqlMasterDbInterceptor::class],
        );
    }
}
