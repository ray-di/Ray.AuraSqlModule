# bear/dbal-module

[Doctrine Dbal](https://github.com/doctrine/dbal) Module for BEAR.Sunday

## Installation

### Composer install

    $ composer require bear/dbal-module
 
### Module install

```php
use BEAR\DbalModule\DbalModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new DbalModule);
    }
}

```
### DI trait

 * [DbalInject](https://github.com/BEARSunday/BEAR.DbalModule/blob/master/src/DbalInject.php) for `Doctrine\DBAL\Driver\Connection` interface
 
### Env

    $_ENV['DBAL_CONFIG'] = 'driver=pdo_sqlite&memory=true'; // dbal config with QUERY_STRING formart 
    // @see http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html

 
