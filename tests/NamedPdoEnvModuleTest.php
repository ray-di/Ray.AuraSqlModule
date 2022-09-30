<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function putenv;

class NamedPdoEnvModuleTest extends TestCase
{
    public function setUp(): void
    {
        putenv('TEST_DSN=sqlite::memory:');
    }

    public function testSingletonModule()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new NamedPdoEnvModule($qualifer, 'TEST_DSN'), __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testMasterSlaveModule()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new NamedPdoEnvModule($qualifer, 'TEST_DSN', 'TEST_USERNAME', 'TEST_PASSWORD', 'SLAVE1,SLAVE2'), __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testMasterSlaveModuleSingletonPdo()
    {
        $qualifer = 'log_db';
        $instance = (new Injector(new NamedPdoEnvModule($qualifer, 'TEST_DSN', 'TEST_USERNAME', 'TEST_PASSWORD', 'SLAVE1,SLAVE2'), __DIR__ . '/tmp'))->getInstance(ExtendedPdoInterface::class, $qualifer);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }
}
