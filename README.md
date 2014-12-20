# bear/aura-sql-module

[Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for BEAR.Sunday

## Installation

### Composer install

    $ composer require bear/aura-sql-module
 
### Module install

```php
use BEAR\AuraSqlModule\AuraSqlModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlModule);
    }
}

```
### DI trait

 * [AuraSqlInject](https://github.com/BEARSunday/BEAR.AuraSqlModule/blob/master/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 
### Env

    $_ENV['PDO_DSN'] = 'sqlite::memory:';
    $_ENV['PDO_USER'] = '';
    $_ENV['PDO_PASSWORD'] = '';

 
