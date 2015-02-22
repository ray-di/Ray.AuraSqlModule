<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\Exception\RollbackException;
use Ray\Di\Injector;

class AuraSqlLocatorModuleTest extends \PHPUnit_Framework_TestCase
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
        $this->model = (new Injector(new AuraSqlLocatorModule($this->locator, ['read'], ['write']), $_ENV['TMP_DIR']))->getInstance(FakeModel::class);
    }

    public function testLocator()
    {
        $this->assertNull($this->model->getPdo());
        $this->model->read();
        $this->assertInstanceOf(ExtendedPdo::class, $this->model->getPdo());
        $this->assertSame($this->slavePdo, $this->model->getPdo());
        $this->model->write();
        $this->assertSame($this->masterPdo, $this->model->getPdo());
    }

    public function testAnnotation()
    {
        $this->assertNull($this->model->getPdo());
        $this->model->slave();
        $this->assertInstanceOf(ExtendedPdo::class, $this->model->getPdo());
        $this->assertSame($this->slavePdo, $this->model->getPdo());
        $this->model->master();
        $this->assertSame($this->masterPdo, $this->model->getPdo());
    }

    public function testTransactional()
    {
        $this->setExpectedException(RollbackException::class);
        $this->model->dbError();
    }
}
