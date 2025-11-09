<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use SensitiveParameter;

final class Connection
{
    private ?ExtendedPdo $pdo = null;

    /**
     * @phpstan-param array<string, mixed> $options
     * @phpstan-param array<string>        $queries
     */
    public function __construct(
        private readonly string $dsn,
        private readonly string $username = '',
        #[SensitiveParameter]
        private readonly string $password = '',
        /** @var array<string, mixed> */
        private readonly array $options = [],
        /** @var array<string> */
        private readonly array $queries = []
    ) {
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
