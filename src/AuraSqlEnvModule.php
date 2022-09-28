<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\AuraSqlModule\Annotation\EnvAuth;
use Ray\Di\AbstractModule;

class AuraSqlEnvModule extends AbstractModule
{
    private string $dsn;
    private string $user;
    private string $password;
    private string $slave;

    /** @var array<mixed> */
    private array $options;

    /**
     * @param string       $dsn      Data Source Name (DSN)
     * @param string       $user     User name for the DSN string
     * @param string       $password Password for the DSN string
     * @param string       $slave    Comma separated slave host list
     * @param array<mixed> $options  A key=>value array of driver-specific connection options
     */
    public function __construct(string $dsn, string $user = '', string $password = '', string $slave = '', array $options = [])
    {
        $this->dsn = $dsn;
        $this->user = $user;
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
        $this->bind()->annotatedWith(EnvAuth::class)->toInstance(
            [
                'dsn' => $this->dsn,
                'user' => $this->user,
                'password' => $this->password,
            ]
        );
        $this->bind()->annotatedWith('pdo_dsn')->toProvider(EnvAuthProvider::class, 'dsn');
        $this->bind()->annotatedWith('pdo_user')->toProvider(EnvAuthProvider::class, 'user');
        $this->bind()->annotatedWith('pdo_pass')->toProvider(EnvAuthProvider::class, 'password');
        $this->bind(ConnectionLocatorInterface::class)->toProvider(ConnectionLocatorProvider::class);
        $this->install(new AuraSqlModule('', '', '', $this->slave, $this->options));
    }
}
