<?php

declare(strict_types=1);

use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\Exception\RollbackException;
use Ray\Di\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

class Fake
{
    public function __construct(
        private ExtendedPdoInterface $pdo
    ) {
    }

    public function init()
    {
        $this->pdo->exec(/** @lang sql */'CREATE TABLE user(name, age)');
    }

    #[Transactional]
    public function insert()
    {
        $this->pdo->perform(/** @lang sql */'INSERT INTO user (name, age) VALUES (?, ?)', ['bear', 10]);
        $this->pdo->exec('*****');
    }
}

$fake = (new Injector(new AuraSqlModule('sqlite::memory:')))->getInstance(Fake::class);
assert($fake instanceof Fake);
$fake->init();
$works = false;
try {
    $fake->insert();
} catch (RollbackException $e) {
    $works = true;
}

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
