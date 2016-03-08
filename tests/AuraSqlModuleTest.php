<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Compiler\DiCompiler;
use Ray\Di\Injector;

class AuraSqlModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $instance = (new Injector(new AuraSqlModule('sqlite::memory:'), $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testCompile()
    {
        (new DiCompiler(new AuraSqlModule('sqlite::memory:'), $_ENV['TMP_DIR']))->compile();
    }

    public function testSlaveModule()
    {
        $module = new AuraSqlModule('mysql:host=localhost;dbname=testdb', 'root', '', 'slave1,slave2');
        $instance = $module->getContainer()->getContainer()['Aura\Sql\ConnectionLocatorInterface-'];
        /* @var $instance \Ray\Di\Instance */
        /* @var $locator ConnectionLocatorInterface */
        $locator = $instance->value;
        $this->assertInstanceOf(ConnectionLocatorInterface::class, $locator);
        $read = $locator->getRead();
        $dsn = $read->getDsn();
        $this->assertContains($dsn, ['mysql:host=slave1;dbname=testdb', 'mysql:host=slave2;dbname=testdb']);
    }
}
