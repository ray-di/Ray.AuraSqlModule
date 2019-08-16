<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\Delete;
use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\Insert;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\Update;
use Aura\SqlQuery\Common\UpdateInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Ray\Di\InjectorInterface;

class AuraSqlQueryModuleTest extends TestCase
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    protected function setUp() : void
    {
        parent::setUp();
        $this->injector = new Injector(new AuraSqlQueryModule('sqlite'), __DIR__ . '/tmp');
    }

    public function testSelect()
    {
        $instance = $this->injector->getInstance(SelectInterface::class);
        $this->assertInstanceOf(Select::class, $instance);
    }

    public function testInsert()
    {
        $instance = $this->injector->getInstance(InsertInterface::class);
        $this->assertInstanceOf(Insert::class, $instance);
    }

    public function testUpdate()
    {
        $instance = $this->injector->getInstance(UpdateInterface::class);
        $this->assertInstanceOf(Update::class, $instance);
    }

    public function testDelete()
    {
        $instance = $this->injector->getInstance(DeleteInterface::class);
        $this->assertInstanceOf(Delete::class, $instance);
    }

    public function testInjectQuery()
    {
        /* @var FakeQueryInject $fakeInject  */
        $fakeInject = (new Injector(new AuraSqlQueryModule('mysql')))->getInstance(FakeQueryInject::class);
        list(, $select, $insert, $update, $delete) = $fakeInject->get();
        $this->assertInstanceOf(SelectInterface::class, $select);
        $this->assertInstanceOf(InsertInterface::class, $insert);
        $this->assertInstanceOf(UpdateInterface::class, $update);
        $this->assertInstanceOf(DeleteInterface::class, $delete);
    }
}
