<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerModule;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlModule extends AbstractModule
{
    const PARSE_PDO_DSN_REGEX = '/(.*?)\:(?:(host|server)=.*?;)?(.*)/i';

    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $slave;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string $dsn        Data Source Name (DSN)
     * @param string $user       User name for the DSN string
     * @param string $password   Password for the DSN string
     * @param string $slave      Comma separated slave host list
     * @param array  $options    A key=>value array of driver-specific connection options
     * @param array  $attributes Attributes to set after connection
     */
    public function __construct(string $dsn, string $user = '', string $password = '', string $slave = '', array $options = [], array $attributes = [])
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->slave = $slave;
        $this->options = $options;
        $this->attributes = $attributes;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->slave ? $this->configureMasterSlaveDsn() : $this->configureSingleDsn();
        // @Transactional
        $this->install(new TransactionalModule);
        $this->install(new AuraSqlPagerModule());
        \preg_match(self::PARSE_PDO_DSN_REGEX, $this->dsn, $parts);
        $dbType = $parts[1] ?? '';
        $this->install(new AuraSqlQueryModule($dbType));
    }

    private function configureSingleDsn()
    {
        $this->bind(ExtendedPdoInterface::class)->toConstructor(ExtendedPdo::class, 'dsn=pdo_dsn,username=pdo_user,password=pdo_pass,options=pdo_options,attributes=pdo_attributes')->in(Scope::SINGLETON);
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_options')->toInstance($this->options);
        $this->bind()->annotatedWith('pdo_attributes')->toInstance($this->options);
    }

    private function configureMasterSlaveDsn()
    {
        $locator = new ConnectionLocator;
        $locator->setWrite('master', new Connection($this->dsn, $this->user, $this->password));
        $i = 1;
        $slaves = \explode(',', $this->slave);
        foreach ($slaves as $slave) {
            $slaveDsn = $this->changeHost($this->dsn, $slave);
            $name = 'slave' . (string) $i++;
            $locator->setRead($name, new Connection($slaveDsn, $this->user, $this->password));
        }
        $this->install(new AuraSqlReplicationModule($locator));
    }

    private function changeHost($dsn, $host) : string
    {
        \preg_match(self::PARSE_PDO_DSN_REGEX, $dsn, $parts);
        if (! $parts) {
            return $dsn;
        }
        $dsn = \sprintf('%s:%s=%s;%s', $parts[1], $parts[2], $host, $parts[3]);

        return $dsn;
    }
}
