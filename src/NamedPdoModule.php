<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;

class NamedPdoModule extends AbstractModule
{
    public const PARSE_PDO_DSN_REGEX = '/(.*?)\:(host|server)=.*?;(.*)/i';

    private string $qualifer;
    private string $dsn;
    private string $user;
    private string $password;
    private string $slave;

    public function __construct(
        string $qualifer,
        string $dsn,
        string $user = '',
        string $pass = '',
        string $slave = ''
    ) {
        $this->qualifer = $qualifer;
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $pass;
        $this->slave = $slave;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->slave ? $this->configureMasterSlaveDsn($this->qualifer, $this->dsn, $this->user, $this->password, $this->slave)
            : $this->configureSingleDsn($this->qualifer, $this->dsn, $this->user, $this->password);
    }

    private function configureSingleDsn(string $qualifer, string $dsn, string $user, string $password): void
    {
        $this->bind(ExtendedPdoInterface::class)
            ->annotatedWith($qualifer)
            ->toConstructor(
                ExtendedPdo::class,
                "dsn={$qualifer}_dsn,username={$qualifer}_username,password={$qualifer}_password"
            );
        $this->bind()->annotatedWith("{$qualifer}_dsn")->toInstance($dsn);
        $this->bind()->annotatedWith("{$qualifer}_username")->toInstance($user);
        $this->bind()->annotatedWith("{$qualifer}_password")->toInstance($password);
    }

    private function configureMasterSlaveDsn(string $qualifer, string $dsn, string $user, string $password, string $slaveList): void
    {
        $locator = ConnectionLocatorFactory::newInstance($dsn, $user, $password, $slaveList);
        $this->install(new AuraSqlReplicationModule($locator, $qualifer));
    }
}
