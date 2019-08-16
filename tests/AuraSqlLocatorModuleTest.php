<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use PHPUnit\Framework\TestCase;
use Ray\AuraSqlModule\Exception\RollbackException;
use Ray\Di\Injector;
use Ray\Di\NullModule;

class AuraSqlLocatorModuleTest extends TestCase
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

    protected function setUp() : void
    {
        $locator = new ConnectionLocator;
        $slave = new Connection('sqlite::memory:');
        $this->slavePdo = $slave();
        $locator->setRead('slave', $slave);
        $master = new Connection('sqlite::memory:');
        $this->masterPdo = $master();
        $locator->setWrite('master', $master);
        $this->locator = $locator;
        $modue = new NullModule;
        $modue->install(new AuraSqlMasterModule('sqlite::memory:', '', ''));
        $modue->install(new AuraSqlLocatorModule($this->locator, ['read'], ['write']));
        $this->model = (new Injector($modue))->getInstance(FakeModel::class);
    }

    public function testLocator()
    {
        $this->assertNull($this->model->getPdo());
        $this->model->read();
        $this->assertInstanceOf(ExtendedPdo::class, $this->model->getPdo());
        $this->assertSame($this->slavePdo, $this->model->getPdo());
        $hasReturn = $this->model->write();
        $this->assertSame($this->masterPdo, $this->model->getPdo());
        $this->assertTrue($hasReturn);
    }

    public function testAnnotation()
    {
        $this->assertNull($this->model->getPdo());
        $hasReturn = $this->model->slave();
        $this->assertInstanceOf(ExtendedPdo::class, $this->model->getPdo());
        $this->assertTrue($hasReturn);
        $this->assertSame($this->slavePdo, $this->model->getPdo());
        $hasReturn = $this->model->master();
        $this->assertTrue($hasReturn);
        $this->assertSame($this->masterPdo, $this->model->getPdo());
    }

    public function testTransactional()
    {
        $this->expectException(RollbackException::class);
        $this->model->dbError();
    }
}
