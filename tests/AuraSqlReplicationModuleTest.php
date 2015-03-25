<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Ray\Di\Injector;

class AuraSqlReplicationModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtendedPdo
     */
    private $slavePdo;

    /**
     * @var ExtendedPdo
     */
    private $masterPdo;

    /**
     * @var ConnectionLocator
     */
    private $locator;

    /**
     * @var FakeModel
     */
    private $model;

    protected function setUp()
    {
        $locator = new ConnectionLocator;
        $slave = new Connection('sqlite::memory:');
        $this->slavePdo = $slave();
        $locator->setRead('slave', $slave);
        $master = new Connection('sqlite::memory:');
        $this->masterPdo = $master();
        $locator->setWrite('master', $master);
        $this->locator = $locator;
    }

    public function testLocatorSlave()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        /* @var  $model FakeRepModel */
        $model = (new Injector(new AuraSqlReplicationModule($this->locator), $_ENV['TMP_DIR']))->getInstance(FakeRepModel::class);
        $this->assertInstanceOf(ExtendedPdo::class, $model->pdo);
        $this->assertSame($this->slavePdo, $model->pdo);
    }

    public function testLocatorMaster()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        /* @var  $model FakeRepModel */
        $model = (new Injector(new AuraSqlReplicationModule($this->locator), $_ENV['TMP_DIR']))->getInstance(FakeRepModel::class);
        $this->assertInstanceOf(ExtendedPdo::class, $model->pdo);
        $this->assertSame($this->masterPdo, $model->pdo);
    }
}
