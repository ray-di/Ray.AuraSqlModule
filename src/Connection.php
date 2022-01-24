<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;

class Connection
{
    /** @var string */
    private $dsn;

    /** @var string */
    private $id;

    /** @var string */
    private $password;

    /** @var array<string> */
    private $options;

    /** @var array<string> */
    private $attributes;

    /** @var ExtendedPdo */
    private $pdo;

    /**
     * @phpstan-param array<string> $options
     * @phpstan-param array<string> $attributes
     */
    public function __construct(string $dsn, string $id = '', string $password = '', array $options = [], array $attributes = [])
    {
        $this->dsn = $dsn;
        $this->id = $id;
        $this->password = $password;
        $this->options = $options;
        $this->attributes = $attributes;
    }

    public function __invoke(): ExtendedPdo
    {
        if (! $this->pdo instanceof ExtendedPdo) {
            $this->pdo = new ExtendedPdo($this->dsn, $this->id, $this->password, $this->options, $this->attributes);
        }

        return $this->pdo;
    }
}
