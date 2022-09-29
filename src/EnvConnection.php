<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;

use function array_rand;
use function explode;
use function getenv;
use function preg_match;
use function sprintf;

final class EnvConnection
{
    private string $dsn;
    private string $username;
    private string $password;

    /** @var array<string> */
    private array $options;

    /** @var array<string> */
    private array $queries;

    /** @var array<ExtendedPdo> */
    private array $pdo = [];
    private ?string $slave;

    /**
     * @phpstan-param array<string> $options
     * @phpstan-param array<string> $queries
     */
    public function __construct(
        string $dsn,
        ?string $slave,
        string $username = '',
        string $password = '',
        array $options = [],
        array $queries = []
    ) {
        $this->dsn = $dsn;
        $this->slave = $slave;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->queries = $queries;
    }

    public function __invoke(): ExtendedPdo
    {
        $dsn = $this->getDsn();
        if (isset($this->pdo[$dsn])) {
            return $this->pdo[$dsn];
        }

        $this->pdo[$dsn] = new ExtendedPdo(
            $dsn,
            (string) getenv($this->username),
            (string) getenv($this->password),
            $this->options,
            $this->queries
        );

        return $this->pdo[$dsn];
    }

    private function getDsn(): string
    {
        // write
        if ($this->slave === null) {
            return (string) getenv($this->dsn);
        }

        // random read
        $slaveList = explode(',', (string) getenv($this->slave));
        $slave = $slaveList[array_rand($slaveList)];

        return $this->changeHost((string) getenv($this->dsn), $slave);
    }

    /**
     * @psalm-pure
     */
    private function changeHost(string $dsn, string $host): string
    {
        preg_match(AuraSqlModule::PARSE_PDO_DSN_REGEX, $dsn, $parts);
        if (! $parts) {
            // @codeCoverageIgnoreStart
            return $dsn;
            // @codeCoverageIgnoreEnd
        }

        return sprintf('%s:%s=%s;%s', $parts[1], $parts[2], $host, $parts[3]);
    }
}
