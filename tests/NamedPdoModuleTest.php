<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class NamedPdoModuleTest extends TestCase
{
    public function testModule()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new NamedPdoModule($qualifer, 'sqlite::memory:'), __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testFakeName()
    {
        $injector = new Injector(new FakeNamedModule, __DIR__ . '/tmp');
        $fakeName = $injector->getInstance(FakeName::class);
        $this->assertInstanceOf(ExtendedPdo::class, $fakeName->pdo);
        $this->assertInstanceOf(ExtendedPdo::class, $fakeName->pdoAnno);
        $this->assertInstanceOf(ExtendedPdo::class, $fakeName->pdoSetterInject);
    }

    public function testReplicationMaster()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $qualifer = 'log_db';
        $instance = (new Injector(new FakeNamedReplicationModule, __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
        /* @var $instance ExtendedPdo */
        $this->assertSame('mysql:host=localhost;dbname=db', $instance->getDsn());
    }

    public function testReplicationSlave()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $qualifer = 'log_db';
        $instance = (new Injector(new FakeNamedReplicationModule, __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
        /* @var $instance ExtendedPdo */
        $this->assertContains('mysql:host=slave', $instance->getDsn());
    }

    public function testNoHost()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new FakeNamedQualifierModule, __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        /* @var $instance ExtendedPdo */
        $this->assertSame('sqlite::memory:', $instance->getDsn());
    }
}
