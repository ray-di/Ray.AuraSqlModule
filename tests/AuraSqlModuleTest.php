<?php
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Ray\Compiler\DiCompiler;
use Ray\Compiler\ScriptInjector;
use Ray\Di\Injector;

class AuraSqlModuleTest extends TestCase
{
    public function testModule()
    {
        $instance = (new Injector(new AuraSqlModule('sqlite::memory:'), $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }

    public function testCompile()
    {
        (new DiCompiler(new AuraSqlModule('sqlite::memory:'), $_ENV['TMP_DIR']))->compile();
        $pdo = (new ScriptInjector($_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdoInterface::class, $pdo);
    }

    public function testMysql()
    {
        $fakeInject = (new Injector(new AuraSqlModule('mysql:host=localhost;dbname=master'), $_ENV['TMP_DIR']))->getInstance(FakeQueryInject::class);
        list($db) = $fakeInject->get();
        $this->assertSame('mysql', $db);
    }

    public function testPgsql()
    {
        $fakeInject = (new Injector(new AuraSqlModule('pgsql:host=localhost;dbname=master'), $_ENV['TMP_DIR']))->getInstance(FakeQueryInject::class);
        list($db) = $fakeInject->get();
        $this->assertSame('pgsql', $db);
    }

    public function testSqlite()
    {
        $fakeInject = (new Injector(new AuraSqlModule('sqlite:memory:'), $_ENV['TMP_DIR']))->getInstance(FakeQueryInject::class);
        list($db) = $fakeInject->get();
        $this->assertSame('sqlite', $db);
    }

    public function testSlaveModule()
    {
        $module = new AuraSqlModule('mysql:host=localhost;dbname=testdb', 'root', '', 'slave1,slave2');
        /** @var \Ray\Di\Instance $instance */
        $instance = $module->getContainer()->getContainer()['Aura\Sql\ConnectionLocatorInterface-'];
        /** @var ConnectionLocatorInterface $locator */
        $locator = $instance->value;
        $this->assertInstanceOf(ConnectionLocatorInterface::class, $locator);
        $read = $locator->getRead();
        $dsn = $read->getDsn();
        $this->assertContains($dsn, ['mysql:host=slave1;dbname=testdb', 'mysql:host=slave2;dbname=testdb']);
    }

    public function testNoHost()
    {
        $instance = (new Injector(new FakeQualifierModule, $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        /* @var $instance ExtendedPdo */
        $this->assertSame('sqlite::memory:', $instance->getDsn());
    }
}
