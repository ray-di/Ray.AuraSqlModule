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
        $this->pdo->exec(/** @lang sql */'CREATE TABLE user(name)');
        $this->pdo->perform(/** @lang sql */'INSERT INTO user (name) VALUES (?)', ['kuma']);
    }

    #[Transactional]
    public function insert()
    {
        $this->pdo->perform(/** @lang sql */'INSERT INTO user (name) VALUES (?)', ['bear']);
        $users = $this->fetch();
        assert(count($users) === 2);
        $this->pdo->exec('*****');
    }

    public function fetch()
    {
        return $this->pdo->fetchAssoc(/** @lang sql */'SELECT * from user');
    }
}

$fake = (new Injector(new AuraSqlModule('sqlite::memory:')))->getInstance(Fake::class);
assert($fake instanceof Fake);
$fake->init();
$rollBacked = false;
$users = $fake->fetch();
try {
    $fake->insert();
} catch (RollbackException $e) {
    $rollBacked = true;
}

echo(count($users) === 1 && $rollBacked ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
