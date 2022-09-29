<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;

class Connection
{
    private string $dsn;
    private string $username;
    private string $password;

    /** @var array<string> */
    private array $options;

    /** @var array<string> */
    private array $queries;
    private ?ExtendedPdo $pdo = null;

    /**
     * @phpstan-param array<string> $options
     * @phpstan-param array<string> $queries
     */
    public function __construct(
        string $dsn,
        string $username = '',
        string $password = '',
        array $options = [],
        array $queries = []
    ) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->queries = $queries;
    }

    public function __invoke(): ExtendedPdo
    {
        if ($this->pdo instanceof ExtendedPdo) {
            return $this->pdo;
        }

        $this->pdo = new ExtendedPdo($this->dsn, $this->username, $this->password, $this->options, $this->queries);

        return $this->pdo;
    }
}
