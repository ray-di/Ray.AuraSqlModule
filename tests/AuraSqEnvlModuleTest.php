<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function putenv;
use function spl_object_hash;

class AuraSqEnvlModuleTest extends TestCase
{
    public function setUp(): void
    {
        putenv('TEST_DSN=mysql:host=localhost;dbname=db');
        putenv('TEST_USER=user1');
        putenv('TEST_PASSWORD=password1');
        // for log db
        putenv('TEST_SLAVE=SLAVE1,SLAVE2');
    }

    public function tearDown(): void
    {
        putenv('TEST_DSN');
        putenv('TEST_USER');
        putenv('TEST_PASSWORD');
        putenv('TEST_SLAVE');
    }

    public function testSingleDbModule(): void
    {
        $module = new AuraSqlEnvModule('TEST_DSN', 'TEST_USER', 'TEST_PASSWORD');
        $injector = new Injector($module, __DIR__ . '/tmp');
        $instance = $injector->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
        $connection = $injector->getInstance(Connection::class);
        $this->assertTrue($connection->isSame('TEST_DSN', 'TEST_USER', 'TEST_PASSWORD', true));
        // test singleton
        $instance2 = $injector->getInstance(ExtendedPdoInterface::class);
        $this->assertSame(spl_object_hash($instance), spl_object_hash($instance2));
    }

    public function testReplicationModule(): void
    {
        $module = new AuraSqlEnvModule('TEST_DSN', 'TEST_USER', 'TEST_PASSWORD', 'TEST_SLAVE');
        $injector = new Injector($module, __DIR__ . '/tmp');
        $instance = $injector->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
        $connectionLocator = $injector->getInstance(ConnectionLocatorInterface::class);
        $read = $connectionLocator->getRead();
        $this->assertInstanceOf(ExtendedPdo::class, $read);
    }
}
