<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;

use function getenv;

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
    private bool $isEnv;

    /**
     * @phpstan-param array<string> $options
     * @phpstan-param array<string> $queries
     */
    public function __construct(string $dsn, string $username = '', string $password = '', array $options = [], array $queries = [], bool $isEnv = false)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->queries = $queries;
        $this->isEnv = $isEnv;
    }

    public function __invoke(): ExtendedPdo
    {
        if ($this->pdo instanceof ExtendedPdo) {
            return $this->pdo;
        }
        $this->pdo = $this->isEnv ?
            new ExtendedPdo((string) getenv($this->dsn), (string) getenv($this->username), (string) getenv($this->password), $this->options, $this->queries) :
            new ExtendedPdo($this->dsn, $this->username, $this->password, $this->options, $this->queries);

        return $this->pdo;
    }
}
