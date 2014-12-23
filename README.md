# ray/aura-sql-module

[Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for Ray.Di

## Installation

### Composer install

    $ composer require ray/aura-sql-module
 
### Module install

```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlModule($dsn, $id, $password);
    }
}

```
### DI trait

 * [AuraSqlInject](https://github.com/Ray-DI/Ray.AuraSqlModule/blob/master/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 

 
