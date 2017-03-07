<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;

class NamedPdoModule extends AbstractModule
{
    const PARSE_PDO_DSN_REGEX = '/(.*?)\:(host|server)=.*?;(.*)/i';

    /**
     * @var string
     */
    private $qualifer;

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
     * @param string $qualifer
     * @param string $dsn
     * @param string $user
     * @param string $pass
     */
    public function __construct($qualifer, $dsn, $user = '', $pass = '', $slave = '')
    {
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
    protected function configure()
    {
        $this->slave ? $this->configureMasterSlaveDsn($this->qualifer, $this->dsn, $this->user, $this->password, $this->slave)
            : $this->configureSingleDsn($this->qualifer, $this->dsn, $this->user, $this->password);
    }

    private function configureSingleDsn($qualifer, $dsn, $user, $password)
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

    private function configureMasterSlaveDsn($qualifer, $dsn, $user, $password, $slaveList)
    {
        $locator = new ConnectionLocator();
        $locator->setWrite('master', new Connection($dsn, $user, $password));
        $i = 1;
        $slaves = explode(',', $slaveList);
        foreach ($slaves as $slave) {
            $slaveDsn = $this->changeHost($dsn, $slave);
            $name = 'slave' . (string) $i++;
            $locator->setRead($name, new Connection($slaveDsn, $user, $password));
        }
        $this->install(new AuraSqlReplicationModule($locator, $qualifer));
    }

    /**
     * @param string $dsn
     * @param string $host
     *
     * @return string
     */
    private function changeHost($dsn, $host)
    {
        preg_match(self::PARSE_PDO_DSN_REGEX, $dsn, $parts);
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
