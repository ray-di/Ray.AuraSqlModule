<?php

declare(strict_types=1);

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

class Fake
{
    public function __construct(
        public ExtendedPdoInterface $pdo
    ) {
    }
}

$fake = (new Injector(new AuraSqlModule('sqlite::memory:')))->getInstance(Fake::class);
assert($fake instanceof Fake);
$works = ($fake->pdo instanceof ExtendedPdo);

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
