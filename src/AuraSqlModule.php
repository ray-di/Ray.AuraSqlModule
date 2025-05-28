<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Override;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use SensitiveParameter;

final class AuraSqlModule extends AbstractModule
{
    public const PARSE_PDO_DSN_REGEX = '/(.*?):(?:(host|server)=.*?;)?(.*)/i';

    /**
     * @param string        $dsn      Data Source Name (DSN)
     * @param string        $user     User name for the DSN string
     * @param string        $password Password for the DSN string
     * @param string        $slave    Comma separated slave host list
     * @param array<string> $options  A key=>value array of driver-specific connection options
     * @param array<string> $queries
     */
    public function __construct(
        private readonly string $dsn,
        private readonly string $user = '',
        #[SensitiveParameter]
        private readonly string $password = '',
        private readonly string $slave = '',
        private readonly array $options = [],
        private readonly array $queries = []
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        $this->slave ? $this->configureMasterSlaveDsn() : $this->configureSingleDsn();
        $this->install(new AuraSqlBaseModule($this->dsn));
    }

    private function configureSingleDsn(): void
    {
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_slave')->toInstance($this->slave);
        $this->bind()->annotatedWith('pdo_options')->toInstance($this->options);
        $this->bind()->annotatedWith('pdo_queries')->toInstance($this->queries);
        $this->bind(ExtendedPdoInterface::class)->toConstructor(
            ExtendedPdo::class,
            'dsn=pdo_dsn,username=pdo_user,password=pdo_pass,options=pdo_options,queries=pdo_queries',
        )->in(Scope::SINGLETON);
    }

    private function configureMasterSlaveDsn(): void
    {
        $locator = ConnectionLocatorFactory::fromInstance(
            $this->dsn,
            $this->user,
            $this->password,
            $this->slave,
            $this->options,
            $this->queries,
        );
        $this->install(new AuraSqlReplicationModule($locator));
    }
}
