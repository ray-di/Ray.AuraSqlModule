<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Write;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\AbstractModule;

use function array_merge;

final class AuraSqlLocatorModule extends AbstractModule
{
    /**
     * @phpstan-param array<string> $readMethods
     * @phpstan-param array<string> $writeMethods
     */
    public function __construct(
        private readonly ConnectionLocatorInterface $connectionLocator,
        /** @var string[] */
        private readonly array $readMethods = [],
        /** @var string[] */
        private readonly array $writeMethods = [],
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
        if ((bool) $this->readMethods && (bool) $this->writeMethods) {
            $this->bind()->annotatedWith(Read::class)->toInstance($this->readMethods);
            /** @psalm-suppress MissingDependency */
            $this->bind()->annotatedWith(Write::class)->toInstance($this->writeMethods);
        }

        $this->bind(ConnectionLocatorInterface::class)->toInstance($this->connectionLocator);
        $methods = array_merge($this->readMethods, $this->writeMethods);
        // @AuraSql
        $this->installLocatorDb($methods);
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
            [AuraSqlSlaveDbInterceptor::class],
        );
        // @WriteConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(WriteConnection::class),
            [AuraSqlMasterDbInterceptor::class],
        );
    }

    /** @param string[] $methods */
    private function installLocatorDb(array $methods): void
    {
        // locator db
        $this->bindInterceptor(
            $this->matcher->annotatedWith(AuraSql::class), // @AuraSql in class
            $this->matcher->logicalAnd(
                new IsInMethodMatcher($methods),
                $this->matcher->logicalNot(
                    $this->matcher->annotatedWith(ReadOnlyConnection::class),
                ),
                $this->matcher->logicalNot(
                    $this->matcher->annotatedWith(Connection::class),
                ),
            ),
            [AuraSqlConnectionInterceptor::class],
        );
    }
}
