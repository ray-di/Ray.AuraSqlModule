<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class AuraSqlEnvModule extends AbstractModule
{
    private string $dsn;
    private string $username;
    private string $password;
    private string $slave;

    /** @var array<string> */
    private array $options;

    /** @var array<string> */
    private array $queries;

    /**
     * @param string        $dsnKey      Env key for Data Source Name (DSN)
     * @param string        $usernameKey Env key for Username for the DSN string
     * @param string        $passwordKey Env key for Password for the DSN string
     * @param string        $slaveKey    Env key for Comma separated slave host list
     * @param array<string> $options     A key=>value array of driver-specific connection options
     * @param array<string> $queries     Queries to execute after the connection.
     */
    public function __construct(
        string $dsnKey,
        string $usernameKey = '',
        string $passwordKey = '',
        string $slaveKey = '',
        array $options = [],
        array $queries = []
    ) {
        $this->dsn = $dsnKey;
        $this->username = $usernameKey;
        $this->password = $passwordKey;
        $this->slave = $slaveKey;
        $this->options = $options;
        $this->queries = $queries;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->install(new NamedPdoEnvModule('', $this->dsn, $this->username, $this->password, $this->slave, $this->options, $this->queries));
        $this->install(new AuraSqlBaseModule($this->dsn));
    }
}
