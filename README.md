# ray/aura-sql-module
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=develop)
[![Build Status](https://travis-ci.org/Ray-Di/Ray.AuraSqlModule.svg?branch=master)](https://travis-ci.org/Ray-Di/Ray.AuraSqlModule)

[Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for [Ray.Di](https://github.com/koriym/Ray.Di)

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
        $this->install(new AuraSqlModule('mysql:host=localhost;dbname=test', 'username', 'password');
    }
}

```
### DI trait

 * [AuraSqlInject](https://github.com/Ray-DI/Ray.AuraSqlModule/blob/master/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 
### Demo

    $ php docs/demo/run.php
    // It works!

### Requiuments

 * PHP 5.4+
 * hhvm
 
