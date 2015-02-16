<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Ray\Di\Injector;

class AuraSqlLocatorModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $locator = new ConnectionLocator;
        $slave = new Connection('sqlite::memory:');
        $slavePdo = $slave();
        $locator->setRead('slave', $slave);
        $master = new Connection('sqlite::memory:');
        $masterPdo = $master();
        $locator->setWrite('master', $master);

        /** @var  $model FakeModel */
        $model = (new Injector(new AuraSqlLocatorModule($locator, ['read'], ['write']), $_ENV['TMP_DIR']))->getInstance(FakeModel::class);
        $this->assertNull($model->pdo);
        $model->read();
        $this->assertInstanceOf(ExtendedPdo::class, $model->pdo);
        $this->assertSame($slavePdo, $model->pdo);
        $model->write();
        $this->assertSame($masterPdo, $model->pdo);
    }
}
