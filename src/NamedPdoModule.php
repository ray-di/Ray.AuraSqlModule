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
    private bool $isEnv;

    public function __construct(
        string $qualifer,
        string $dsn,
        string $user = '',
        string $pass = '',
        string $slave = '',
        bool $isEnv = false
    ) {
        $this->qualifer = $qualifer;
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $pass;
        $this->slave = $slave;
        $this->isEnv = $isEnv;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->slave ? $this->configureMasterSlaveDsn()
            : $this->configureSingleDsn();
    }

    private function configureSingleDsn(): void
    {
        $this->bind(ExtendedPdoInterface::class)
            ->annotatedWith($this->qualifer)
            ->toConstructor(
                ExtendedPdo::class,
                "dsn={$this->qualifer}_dsn,username={$this->qualifer}_username,password={$this->qualifer}_password"
            );
        $this->bind()->annotatedWith("{$this->qualifer}_dsn")->toInstance($this->dsn);
        $this->bind()->annotatedWith("{$this->qualifer}_username")->toInstance($this->user);
        $this->bind()->annotatedWith("{$this->qualifer}_password")->toInstance($this->password);
    }

    private function configureMasterSlaveDsn(): void
    {
        $locator = ConnectionLocatorFactory::newInstance($this->dsn, $this->user, $this->password, $this->slave, $this->isEnv);
        $this->install(new AuraSqlReplicationModule($locator, $this->qualifer));
    }
}
