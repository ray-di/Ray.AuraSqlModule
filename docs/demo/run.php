<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\Injector;

class Fake
{
    use AuraSqlInject;

    public function foo()
    {
        return $this->pdo;
    }
}

$fake = (new Injector(new AuraSqlModule('sqlite::memory:')))->getInstance(Fake::class);
/* @var $fake Fake */
$works = ($fake->foo() instanceof ExtendedPdo);

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
