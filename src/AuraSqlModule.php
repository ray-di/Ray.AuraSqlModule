<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function preg_match;

class AuraSqlModule extends AbstractModule
{
    public const PARSE_PDO_DSN_REGEX = '/(.*?):(?:(host|server)=.*?;)?(.*)/i';

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
        $this->slave ? $this->configureMasterSlaveDsn() : $this->configureSingleDsn();
        // @Transactional
        $this->install(new TransactionalModule());
        $this->install(new AuraSqlPagerModule());
        preg_match(self::PARSE_PDO_DSN_REGEX, $this->dsn, $parts);
        $dbType = $parts[1] ?? '';
        $this->install(new AuraSqlQueryModule($dbType));
    }

    private function configureSingleDsn(): void
    {
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_slave')->toInstance($this->slave);
        $this->bind()->annotatedWith('pdo_options')->toInstance($this->options);
        $this->bind()->annotatedWith('pdo_attributes')->toInstance($this->options);
        $this->bind(ExtendedPdoInterface::class)->toConstructor(ExtendedPdo::class, 'dsn=pdo_dsn,username=pdo_user,password=pdo_pass,options=pdo_options,attributes=pdo_attributes')->in(Scope::SINGLETON);
    }

    private function configureMasterSlaveDsn(): void
    {
        $context = '';
        $this->bind()->annotatedWith('pdo_locator_dsn')->toInstance([$context => $this->dsn]);
        $this->bind()->annotatedWith('pdo_locator_user')->toInstance([$context => $this->user]);
        $this->bind()->annotatedWith('pdo_locator_pass')->toInstance([$context => $this->password]);
        $this->bind()->annotatedWith('pdo_locator_slave')->toInstance([$context => $this->slave]);
        $this->bind(ConnectionLocatorInterface::class)->annotatedWith($context)->toProvider(ConnectionLocatorProvider::class, $context)->in(Scope::SINGLETON);
        // ReadOnlyConnection when GET, otherwise WriteConnection
        $this->bind(ExtendedPdoInterface::class)->toProvider(AuraSqlReplicationDbProvider::class);
    }
}
