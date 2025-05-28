<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Override;
use Ray\Di\AbstractModule;

class AuraSqlEnvModule extends AbstractModule
{
    /**
     * @param string        $dsn      Env key for Data Source Name (DSN)
     * @param string        $username Env key for Username for the DSN string
     * @param string        $password Env key for Password for the DSN string
     * @param string        $slave    Env key for Comma separated slave host list
     * @param array<string> $options  A key=>value array of driver-specific connection options
     * @param array<string> $queries  Queries to execute after the connection.
     */
    public function __construct(
        private readonly string $dsn,
        private readonly string $username = '',
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
        $this->install(new NamedPdoEnvModule('', $this->dsn, $this->username, $this->password, $this->slave, $this->options, $this->queries));
        $this->install(new AuraSqlBaseModule($this->dsn));
    }
}
