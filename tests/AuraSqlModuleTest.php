<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Injector;

class AuraSqlModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testModule()
    {
        $_ENV['PDO_DSN'] = 'sqlite::memory:';
        $_ENV['PDO_USER'] = '';
        $_ENV['PDO_PASSWORD'] = '';
        $instance = (new Injector(new AuraSqlModule, $_ENV['TMP_DIR']))->getInstance(ExtendedPdoInterface::class);
        $this->assertInstanceOf(ExtendedPdo::class, $instance);
    }
}
