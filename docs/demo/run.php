<?php

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\Injector;

class Fake
{
    use AuraSqlInject;

    public function getDb()
    {
        return $this->db;
    }
}

$_ENV['PDO_DSN'] = 'sqlite::memory:';
$_ENV['PDO_USER'] = '';
$_ENV['PDO_PASSWORD'] = '';
$fake = (new Injector(new AuraSqlModule))->getInstance(Fake::class);
var_dump($fake->getDb());
$works = ($fake->getDb() instanceof ExtendedPdo);

echo ($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;

