<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Injector;

class NamedPdoModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new NamedPdoModule($qualifer, 'sqlite::memory:'), $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testFakeName()
    {
        $injector = new Injector(new FakeNamedModule, $_ENV['TMP_DIR']);
        $fakeName = $injector->getInstance(FakeName::class);
        $this->assertInstanceOf(ExtendedPdo::class, $fakeName->pdo);
    }
}
