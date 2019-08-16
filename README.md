# Ray.AuraSqlModule

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/quality-score.png?b=1.x)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=1.x)
[![Code Coverage](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/coverage.png?b=1.x)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/?branch=1.x)
[![Build Status](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/badges/build.png?b=1.x)](https://scrutinizer-ci.com/g/Ray-Di/Ray.AuraSqlModule/build-status/1.x)
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
        $this->install(
            new AuraSqlModule(
                'mysql:host=localhost;dbname=test',
                'username',
                'password',
                'slave1,slave2,slave3' // optional slave server list
                $options,              // optional key=>value array of driver-specific connection options
                $attributes            // optional key=>value attriburtes
        );
    }
}
```
### DI trait

 * [AuraSqlInject](https://github.com/ray-di/Ray.AuraSqlModule/blob/1.x/src/AuraSqlInject.php) for `Aura\Sql\ExtendedPdoInterface` interface
 
 ## Replication
 
 Installing `AuraSqlReplicationModule` using a `connection locator` for master/slave connections.
 
 ```php?start_inline
 use Ray\Di\AbstractModule;
 use Ray\AuraSqlModule\AuraSqlModule;
 use Ray\AuraSqlModule\Annotation\AuraSqlConfig;
 use Aura\Sql\ConnectionLocator;
 
 class AppModule extends AbstractModule
 {
     protected function configure()
     {
         $locator = new ConnectionLocator;
         $locator->setWrite('master', new Connection('mysql:host=localhost;dbname=master', 'id', 'pass'));
         $locator->setRead('slave1',  new Connection('mysql:host=localhost;dbname=slave1', 'id', 'pass'));
         $locator->setRead('slave2',  new Connection('mysql:host=localhost;dbname=slave2', 'id', 'pass'));
         $this->install(new AuraSqlReplicationModule($locator));
     }
 }
 
 ```
 
You will now have a slave db connection when using HTTP GET, or a master db connection in other HTTP methods.

## Multiple DB

You may want to inject different connection destinations on the same DB interface with `@Named($qaulifier)` annotation.
Two modules are provided. `NamedPdoModule` is for non replication use. and `AuraSqlReplicationModule` is for replication use.


```php
/**
 * @Inject
 * @Named("log_db")
 */
public function setLoggerDb(ExtendedPdoInterface $pdo)
{
    // ...
}
```


### with no replication

Use `NamedPdoModule ` to inject different named `Pdo` instance for **non** Replication use.
For instance, This module install `log_db` named `Pdo` instance.

```php
class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new NamedPdoModule('log_db', 'mysql:host=localhost;dbname=log', 'username', 
    }
}
```

### with replication

You can set `$qaulifer` in 2nd parameter of AuraSqlReplicationModule.

```php
class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new AuraSqlReplicationModule($locator, 'log_db'));
    }
}
```

## Transaction

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

**ExtendedPdo**

```php

use Ray\AuraSqlModule\AuraSqlPagerInject;

class Foo
{
    use AuraSqlPagerInject;

    publuc function bar()
    {   
        // ...     
        $pager = $this->pagerFactory->newInstance($pdo, $sql, $params, 10, '/?page={page}&category=sports'); // 10 items per page
        $page = $pager[2]; // page 2
```

**Select query builder**

```php
use Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerInject;

class Foo
{
    use AuraSqlQueryPagerInject;

    publuc function bar()
    {
        // ...     
        $pager = $this->queryPagerFactory->newInstance($pdo, $select, 10, '/?page={page}&category=sports');
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
### View

The view template can be changed with binding. See more at [Pagerfanta](https://github.com/whiteoctober/Pagerfanta#views).

```php
use Pagerfanta\View\Template\TemplateInterface;
use Pagerfanta\View\Template\TwitterBootstrap3Template;
use Ray\AuraSqlModule\Annotation\PagerViewOption;

$this->bind(TemplateInterface::class)->to(TwitterBootstrap3Template::class);
$this->bind()->annotatedWith(PagerViewOption::class)->toInstance($pagerViewOption);

```

## Demo

    $ php docs/demo/run.php
    // It works!
