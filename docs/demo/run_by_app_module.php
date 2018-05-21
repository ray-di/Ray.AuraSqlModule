<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
use Aura\Sql\ExtendedPdo;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\AuraSqlModule\AuraSqlInject;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

$loader = require \dirname(\dirname(__DIR__)) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

class Fake
{
    use AuraSqlInject;

    public function foo()
    {
        return $this->pdo;
    }
}

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlModule('sqlite::memory:'));
        // $this->install(new AuraSqlModule('mysql:host=localhost;dbname=test', 'username', 'password'));
    }
}

$fake = (new Injector(new AppModule))->getInstance(Fake::class);
/* @var $fake Fake */
$works = ($fake->foo() instanceof ExtendedPdo);

echo($works ? 'It works!' : 'It DOES NOT work!') . PHP_EOL;
