<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;

class AuraSqlEnvModule extends AbstractModule
{
    private string $dsn;
    private string $username;
    private string $password;
    private string $slave;

    /** @var array<mixed> */
    private array $options;

    /**
     * @param string       $dsn      Data Source Name (DSN)
     * @param string       $username User name for the DSN string
     * @param string       $password Password for the DSN string
     * @param string       $slave    Comma separated slave host list
     * @param array<mixed> $options  A key=>value array of driver-specific connection options
     */
    public function __construct(
        string $dsn,
        string $username = '',
        string $password = '',
        string $slave = '',
        array $options = []
    ){
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->slave = $slave;
        $this->options = $options;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind(Connection::class)->toInstance(
            new Connection($this->dsn, $this->username, $this->password, [], [], true)
        );
        $this->bind(ExtendedPdoInterface::class)->toProvider(ExtendedPdoProvider::class);
        if ($this->slave) {
            $locator = ConnectionLocatorFactory::newInstance(
                $this->dsn,
                $this->username,
                $this->password,
                $this->slave,
                true
            );
            $this->install(new AuraSqlReplicationModule($locator));
        }

        $this->install(new AuraSqlModule('', '', '', '', $this->options));
    }
}
