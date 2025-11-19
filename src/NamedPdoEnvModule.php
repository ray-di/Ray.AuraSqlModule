<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use Ray\Di\AbstractModule;

final class NamedPdoEnvModule extends AbstractModule
{
    public const string PARSE_PDO_DSN_REGEX = '/(.*?)\:(host|server)=.*?;(.*)/i';

    /**
     * @param string              $qualifer Qualifer for ExtendedPdoInterface
     * @param string              $dsn      Data Source Name (DSN)
     * @param string              $username User name for the DSN string
     * @param string              $password Password for the DSN string
     * @param string              $slave    Comma separated slave host list
     * @param array<string,mixed> $options  A key=>value array of driver-specific connection options
     * @param array<string>       $queries  Queries to execute after the connection.
     */
    public function __construct(
        private readonly string $qualifer,
        private readonly string $dsn,
        private readonly string $username = '',
        private readonly string $password = '',
        private readonly string $slave = '',
        /** @var array<string, mixed> */
        private readonly array $options = [],
        /** @var array<string> */
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
        $this->slave ? $this->configureMasterSlaveDsn()
            : $this->configureSingleDsn();
    }

    private function configureSingleDsn(): void
    {
        $connection = new EnvConnection(
            $this->dsn,
            null,
            $this->username,
            $this->password,
            $this->options,
            $this->queries,
        );
        $connectionBinding = $this->bind(EnvConnection::class);
        if ($this->qualifer !== '') {
            $connectionBinding = $connectionBinding->annotatedWith($this->qualifer);
        }

        $connectionBinding->toInstance($connection);

        $pdoBinding = $this->bind(ExtendedPdoInterface::class);
        if ($this->qualifer !== '') {
            $pdoBinding = $pdoBinding->annotatedWith($this->qualifer);
        }

        $pdoBinding->toProvider(
            NamedExtendedPdoProvider::class,
            $this->qualifer,
        );
    }

    private function configureMasterSlaveDsn(): void
    {
        $locator = ConnectionLocatorFactory::fromEnv(
            $this->dsn,
            $this->username,
            $this->password,
            $this->slave,
            $this->options,
            $this->queries,
        );
        $this->install(new AuraSqlReplicationModule($locator, $this->qualifer));
    }
}
