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
        $locator->setRead('slave1', new Connection('sqlite::memory:'));
        $locator->setRead('slave2', new Connection('sqlite::memory:'));
        $locator->setWrite('master', new Connection('sqlite::memory:'));
        /** @var  $model FakeModel */
        $model = (new Injector(new AuraSqlLocatorModule($locator, ['read'], ['write']), $_ENV['TMP_DIR']))->getInstance(FakeModel::class);
        $this->assertNull($model->pdo);
        $model->read();
        $this->assertInstanceOf(ExtendedPdo::class, $model->pdo);
    }
}
