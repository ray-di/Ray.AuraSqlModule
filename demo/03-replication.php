<?php

declare(strict_types=1);

use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\AuraSqlModule\AuraSqlReplicationModule;
use Ray\AuraSqlModule\Connection;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

$_ENV['master'] = '/tmp/master';
$_ENV['slave'] = '/tmp/slave';

class Fake
{
    use AuraSqlInject;

    public function getPdo()
    {
        return $this->pdo;
    }
}

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $locator = new ConnectionLocator();
        $locator->setWrite('master', new Connection($_ENV['master']));
        $locator->setRead('slave1', new Connection($_ENV['slave']));
        $this->install(new AuraSqlReplicationModule($locator));
    }
}
post: {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $fake = (new Injector(new AppModule()))->getInstance(Fake::class);
    assert($fake instanceof Fake);
    $prop = new ReflectionProperty(ExtendedPdo::class, 'args');
    $prop->setAccessible(true);
    $connection = $prop->getValue($fake->getPdo())[0];
    $isMaster = $connection === $_ENV['master'];
}
get: {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $fake = (new Injector(new AppModule()))->getInstance(Fake::class);
    assert($fake instanceof Fake);
    $connection = $prop->getValue($fake->getPdo())[0];
    $isSlave = $connection === $_ENV['slave'];
}

$works = $isMaster && $isSlave;

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
