<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\EnvDsn;
use Ray\AuraSqlModule\Annotation\EnvPassword;
use Ray\AuraSqlModule\Annotation\EnvUser;
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
        $this->bind()->annotatedWith(EnvDsn::class)->toInstance($this->dsn);
        $this->bind()->annotatedWith(EnvUser::class)->toInstance($this->user);
        $this->bind()->annotatedWith(EnvPassword::class)->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_dsn')->toProvider(AuthDsnProvider::class);
        $this->bind()->annotatedWith('pdo_user')->toProvider(AuthUserProvider::class);
        $this->bind()->annotatedWith('pdo_pass')->toProvider(AuthPasswordProvider::class);
        $this->install(new AuraSqlModule('', '', '', $this->slave, $this->options));
    }
}
