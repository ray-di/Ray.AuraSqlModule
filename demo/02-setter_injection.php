<?php

declare(strict_types=1);

use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

class Fake
{
    use AuraSqlInject;

    public function getPdo()
    {
        return $this->pdo;
    }
}

$fake = (new Injector(new AuraSqlModule('sqlite::memory:')))->getInstance(Fake::class);
assert($fake instanceof Fake);
$works = ($fake->getPdo() instanceof ExtendedPdo);

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
