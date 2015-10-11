<?php
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\Delete;
use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\Insert;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\Update;
use Aura\SqlQuery\Common\UpdateInterface;
use Ray\Di\Injector;
use Ray\Di\InjectorInterface;

class AuraSqlQueryModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InjectorInterface
     */
    private $injector;

    protected function setUp()
    {
        parent::setUp();
        $this->injector = new Injector(new AuraSqlQueryModule('sqlite'), $_ENV['TMP_DIR']);
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
}
