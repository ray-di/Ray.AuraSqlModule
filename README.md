# Ray.AuraSqlModule

[![codecov](https://codecov.io/gh/ray-di/Ray.AuraSqlModule/branch/1.x/graph/badge.svg?token=gcWaftzoXp)](https://codecov.io/gh/ray-di/Ray.AuraSqlModule)
[![Type Coverage](https://shepherd.dev/github/ray-di/Ray.AuraSqlModule/coverage.svg)](https://shepherd.dev/github/ray-di/Ray.AuraSqlModule)
[![Continuous Integration](https://github.com/ray-di/Ray.AuraSqlModule/actions/workflows/continuous-integration.yml/badge.svg?branch=2.x)](https://github.com/ray-di/Ray.AuraSqlModule/actions/workflows/continuous-integration.yml)

An [Aura.Sql](https://github.com/auraphp/Aura.Sql) Module for [Ray.Di](https://github.com/koriym/Ray.Di)

## Installation

```bash
composer require ray/aura-sql-module
```

## Getting started

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
                'slave1,slave2,slave3', // optional slave server list
                $options,               // optional key=>value array of driver-specific connection options
                $queries                // Queries to execute after the connection.
            )
        );
    }
}
```

Use AuraSqlEnvModule to get the value from the environment variable each time at runtime, instead of specifying the value directly.

```php
$this->install(
    new AuraSqlEnvModule(
        'PDO_DSN',             // getenv('PDO_DSN')
        'PDO_USER',            // getenv('PDO_USER')
        'PDO_PASSWORD',        // getenv('PDO_PASSWORD')
        'PDO_SLAVE',           // getenv('PDO_SLAVE')
        $options,              // optional key=>value array of driver-specific connection options
        $queries               // Queries to execute after the connection.
    )
);
```

## Replication

Installing `AuraSqlReplicationModule` using a `connection locator` for master/slave connections.

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
        $locator->setWrite('master', new Connection('mysql:host=localhost;dbname=master', 'id', 'pass'));
        $locator->setRead('slave1',  new Connection('mysql:host=localhost;dbname=slave1', 'id', 'pass'));
        $locator->setRead('slave2',  new Connection('mysql:host=localhost;dbname=slave2', 'id', 'pass'));
        $this->install(new AuraSqlReplicationModule($locator));
    }
}
```

You will now have a slave db connection when using HTTP GET, or a master db connection in other HTTP methods.

## Multiple DB

You may want to inject different connection destinations on the same DB interface with `#[Named($qualifier)]` attribute.
Two modules are provided. `NamedPdoModule` is for non replication use. and `AuraSqlReplicationModule` is for replication use.

```php
#[Inject]
public function setLoggerDb(#[Named('log_db')] ExtendedPdoInterface $pdo)
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
        $this->install(new NamedPdoModule('log_db', 'mysql:host=localhost;dbname=log', 'username', 'password'));
    }
}
```

Or

```php
class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new NamedPdoEnvModule('log_db', 'LOG_DSN', 'LOG_USERNAME', 'LOG_PASSWORD'));
    }
}
```

### with replication

You can set `$qualifier` in 2nd parameter of AuraSqlReplicationModule.

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

Any method marked with `#[Transactional]` will have a transaction started before, and ended after it is called.

```php
use Ray\AuraSqlModule\Annotation\WriteConnection; // important
use Ray\AuraSqlModule\Annotation\Transactional;   // important

class User
{
    public $pdo;

    #[WriteConnection, Transactional]
    public function write()
    {
         // $this->pdo->rollback(); when exception thrown.
    }
}
```

## Query Builder

[Aura.SqlQuery](https://github.com/auraphp/Aura.SqlQuery) provides query builders for MySQL, Postgres, SQLite, and Microsoft SQL Server. Following four interfaces are bound and can be injected via constructor:

* `Aura\SqlQuery\Common\SelectInterface`
* `Aura\SqlQuery\Common\InsertInterface`
* `Aura\SqlQuery\Common\UpdateInterface`
* `Aura\SqlQuery\Common\DeleteInterface`

```php
use Aura\SqlQuery\Common\SelectInterface;
use Aura\Sql\ExtendedPdoInterface;

class UserRepository
{
    public function __construct(
        private readonly SelectInterface $select,
        private readonly ExtendedPdoInterface $pdo
    ) {}

    public function findById(int $id): array
    {
        $statement = $this->select
            ->distinct()                    // SELECT DISTINCT
            ->cols([                        // select these columns
                'id',                       // column name
                'name AS namecol',          // one way of aliasing
                'col_name' => 'col_alias',  // another way of aliasing
                'COUNT(foo) AS foo_count'   // embed calculations directly
            ])
            ->from('users AS u')            // FROM these tables
            ->where('id = :id')
            ->getStatement();

        return $this->pdo->fetchAssoc($statement, ['id' => $id]);
    }
}
```

### Multiple Query Builders

```php
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\UpdateInterface;
use Aura\Sql\ExtendedPdoInterface;

class UserService
{
    public function __construct(
        private readonly SelectInterface $select,
        private readonly InsertInterface $insert,
        private readonly UpdateInterface $update,
        private readonly ExtendedPdoInterface $pdo
    ) {}

    public function createUser(array $userData): int
    {
        $statement = $this->insert
            ->into('users')
            ->cols($userData)
            ->getStatement();

        $this->pdo->perform($statement, $this->insert->getBindValues());
        
        return (int) $this->pdo->lastInsertId();
    }

    public function updateUser(int $id, array $userData): bool
    {
        $statement = $this->update
            ->table('users')
            ->cols($userData)
            ->where('id = :id')
            ->bindValue('id', $id)
            ->getStatement();

        return $this->pdo->perform($statement, $this->update->getBindValues());
    }
}
```

## Pagination

Pagination service is provided for both `ExtendedPdo` raw sql and `Select` query builder.

**ExtendedPdo**

```php
use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactoryInterface;
use Aura\Sql\ExtendedPdoInterface;

class UserListService
{
    public function __construct(
        private readonly AuraSqlPagerFactoryInterface $pagerFactory,
        private readonly ExtendedPdoInterface $pdo
    ) {}

    public function getUserList(int $page): Page
    {
        $sql = 'SELECT * FROM users WHERE active = :active';
        $params = ['active' => 1];
        $pager = $this->pagerFactory->newInstance($this->pdo, $sql, $params, 10, '/?page={page}&category=users');
        
        return $pager[$page];
    }
}
```

**Select query builder**

```php
use Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerFactoryInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\Sql\ExtendedPdoInterface;

class ProductListService
{
    public function __construct(
        private readonly AuraSqlQueryPagerFactoryInterface $queryPagerFactory,
        private readonly SelectInterface $select,
        private readonly ExtendedPdoInterface $pdo
    ) {}

    public function getProductList(int $page, string $category): Page
    {
        $select = $this->select
            ->from('products')
            ->where('category = :category')
            ->bindValue('category', $category);
            
        $pager = $this->queryPagerFactory->newInstance($this->pdo, $select, 10, '/?page={page}&category=' . $category);
        
        return $pager[$page];
    }
}
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

It is iterable.

```php
foreach ($page as $item) {
    // ...
}
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

## Profile

To log SQL execution, install `AuraSqlProfileModule`.
It will be logged by a logger bound to the [PSR-3](https://www.php-fig.org/psr/psr-3/) logger. This example binds a minimal function logger created in an anonymous class.

```php
class DevModule extends AbstractModule
{
    protected function configure()
    {
        // ...
        $this->install(new AuraSqlProfileModule());
        $this->bind(LoggerInterface::class)->toInstance(
            new class extends AbstractLogger {
                /** @inheritDoc */
                public function log($level, $message, array $context = [])
                {
                    $replace = [];
                    foreach ($context as $key => $val) {
                        if (! is_array($val) && (! is_object($val) || method_exists($val, '__toString'))) {
                            $replace['{' . $key . '}'] = $val;
                        }
                    }
            
                    error_log(strtr($message, $replace));
                }
            }
        );
    }
}
```
