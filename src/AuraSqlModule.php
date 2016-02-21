<?php
/**
 * This file is part of the Ray.AuraSqlModule package
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
     * @param string $dsn
     * @param string $user
     * @param string $password
     * @param string $slave    comma separated slave host list
     */
    public function __construct($dsn, $user = '', $password = '', $slave = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->slave = $slave;
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
    }

    private function configureSingleDsn()
    {
        $this->bind(ExtendedPdoInterface::class)->toConstructor(ExtendedPdo::class, 'dsn=pdo_dsn,username=pdo_user,passwd=pdo_pass,options=pdo_option')->in(Scope::SINGLETON);
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_option')->toInstance([]);
    }

    private function configureMasterSlaveDsn()
    {
        $locator = new ConnectionLocator;
        $locator->setWrite('master', new Connection($this->dsn, $this->user, $this->password));
        $i = 1;
        $slaves = explode(',', $this->slave);
        foreach ($slaves as $slave) {
            $slaveDsn = $this->chageHost($this->dsn, $slave);
            $name = 'slave' . (string) $i++;
            $locator->setRead($name, new Connection($slaveDsn, $this->user, $this->password));
        }
        $this->install(new AuraSqlReplicationModule($locator));
    }

    /**
     * @param string $dsn
     * @param string $host
     *
     * @return string
     */
    private function chageHost($dsn, $host)
    {
        preg_match("/(.*?)\:(host|server)=.*?;(.*)/i", $dsn, $parts);
        if (empty($parts)) {
            return $dsn;
        }
//        [
//            0 => 'mysql:host=localhost;port=3307;dbname=testdb',
//            1 => 'mysql',
//            2 => 'host',
//            3 => 'port=3307;dbname=testdb'
//        ]
        $dsn = sprintf('%s:%s=%s;%s', $parts[1], $parts[2], $host, $parts[3]);

        return $dsn;
    }
}
