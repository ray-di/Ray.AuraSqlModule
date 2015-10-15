# Ray.AuraSqlModule

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ray-di/Ray.AuraSqlModule/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ray-di/Ray.AuraSqlModule/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/ray-di/Ray.AuraSqlModule/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/ray-di/Ray.AuraSqlModule/?branch=develop)
[![Build Status](https://travis-ci.org/ray-di/Ray.AuraSqlModule.svg?branch=1.x)](https://travis-ci.org/ray-di/Ray.AuraSqlModule)

[Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for [Ray.Di](https://github.com/koriym/Ray.Di)

## Installation

### Composer install

    $ composer require ray/aura-sql-module
 
### Module install

```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\AuraSqlQueryModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlModule('mysql:host=localhost;dbname=test', 'username', 'password');
        $this->install(new AuraSqlQueryModule('mysql'); // optional query builder
    }
}
```
### DI trait

 * [AuraSqlInject](https://github.com/ray-di/Ray.AuraSqlModule/blob/master/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 
#### Master / Slave database

Frequently, high-traffic PHP applications use multiple database servers, generally one for writes, and one or more for reads.
With `AuraSqlReplicationModule`, master / slave database is automatically chosen by `$_SERVER['REQUEST_METHOD']` value. (slave is chosen only when request is `GET`)
 
```php
use Ray\Di\AbstractModule;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;
use Aura\Sql\ConnectionLocator;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $locator = new ConnectionLocator;
        $locator->setWrite('master', new Connection('mysql:host=localhost;dbname=master', 'username', 'password'));
        $locator->setRead('slave1', new Connection('mysql:host=localhost;dbname=slave1', 'username', 'password'));
        $locator->setRead('slave2', new Connection('mysql:host=localhost;dbname=slave2', 'username', 'password'));
        $this->install(new AuraSqlReplicationModule($locator));
    }
}

```

Or when `@ReadOnlyConnection` annotated method is called, Read-only `$pdo`(slave database) is injected to the `$pdo` property. Or `@WriteConnection` for master database connection.

```php

use Ray\AuraSqlModule\Annotation\ReadOnlyConnection; // important
use Ray\AuraSqlModule\Annotation\WriteConnection;    // important

class User
{
    public $pdo;

    /**
     * @ReadOnlyConnection
     */
    public function read()
    {
         $this->pdo: // slave db
    }

    /**
     * @WriteConnection
     */
    public function write()
    {
         $this->pdo: // master db
    }
}
```

Master / slave database is automatically switched to inject 

```php

use Ray\AuraSqlModule\Annotation\AuraSql; // important

/**
 * @AuraSql
 */
class User
{
    public $pdo;

    public function read()
    {
         $this->pdo: // slave db
    }

    public function write()
    {
         $this->pdo: // master db
    }
}
```

Any method marked with `@Transactional` will have a transaction started before, and ended after it is called.

```php

use Ray\AuraSqlModule\Annotation\WriteConnection; // important
use Ray\AuraSqlModule\Annotation\Transactional;   // important

class User
{
    public $pdo;

    /**
     * @WriteConnection
     * @Transactional
     */
    public function write()
    {
         // $this->pdo->rollback(); when exception thrown.
    }
}
```
## Query Builder

[Aura.SqlQuery](https://github.com/auraphp/Aura.SqlQuery) provides query builders for MySQL, Postgres, SQLite, and Microsoft SQL Server. Following four interfaces are bound and setter trait for them are available.

QueryBuilder interface
 * `Aura\SqlQuery\Common\SelectInterface`
 * `Aura\SqlQuery\Common\InsertInterface`
 * `Aura\SqlQuery\Common\UpdateInterface`
 * `Aura\SqlQuery\Common\DeleteInterface`

QueryBuilder setter trait
 * `Ray\AuraSqlModule\AuraSqlSelectInject`
 * `Ray\AuraSqlModule\AuraSqlInsertInject`
 * `Ray\AuraSqlModule\AuraSqlUpdateInject`
 * `Ray\AuraSqlModule\AuraSqlDeleteInject`

```php
use Ray\AuraSqlModule\AuraSqlSelectInject;
clas Foo
{
    use AuraSqlSelectInject;
    
    public function bar()
    {
        ```php
        /* @var $select \Aura\SqlQuery\Common\SelectInterface */
        $this->select // 
            ->distinct()                    // SELECT DISTINCT
            ->cols(array(                   // select these columns
                'id',                       // column name
                'name AS namecol',          // one way of aliasing
                'col_name' => 'col_alias',  // another way of aliasing
                'COUNT(foo) AS foo_count'   // embed calculations directly
            ))
            ->from('foo AS f');              // FROM these tables
        $sth = $this->pdo->prepare($this->select->getStatement());
        // bind the values and execute
        $sth->execute($this->select->getBindValues());
        // get the results back as an associative array
        $result = $sth->fetch(PDO::FETCH_ASSOC);
         = $sth->fetch(PDO::FETCH_ASSOC);
```

## Pagination

Pagination service is provided for both `ExtendedPdo` raw sql and `Select` query builder. 

```php
// for ExtendedPdo
/* @var $factory \Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactoryInterface */
$pager = $factory->newInstance($pdo, $sql, $params, 10, '/?page={page}&category=sports'); // 10 items per page
$page = $pager[2]; // page 2

// for Select
/* @var $factory \Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerFactoryInterface */
$pager = $factory->newInstance($pdo, $select, 10, '/?page={page}&category=sports');
$page = $pager[2]; // page 2
```
An array access with page number returns `Page` value object.
```php
/* @var Pager \Ray\AuraSqlModule\Pagerfanta\Page */

// $page->data // sliced data
// $page->current;
// $page->total
// $page->hasNext
// $page->hasPrevious
// $page->maxPerPage;
// (string) $page // pager html
```
It is iteratable.
```php
foreach ($page as $item) {
 // ...
```
### Demo

    $ php docs/demo/run.php
    // It works!

### Requirements

 * PHP 5.4+
 * hhvm
 
