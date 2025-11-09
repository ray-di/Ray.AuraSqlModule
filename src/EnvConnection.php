<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use SensitiveParameter;

use function array_rand;
use function explode;
use function getenv;
use function preg_match;
use function sprintf;

final class EnvConnection
{
    /** @var array<ExtendedPdo> */
    private static array $pdo = [];

    /**
     * @phpstan-param array<string, mixed> $options
     * @phpstan-param array<string>        $queries
     */
    public function __construct(
        private readonly string $dsn,
        private readonly ?string $slave,
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
        $dsn = $this->getDsn();
        if (isset(self::$pdo[$dsn])) {
            return self::$pdo[$dsn];
        }

        self::$pdo[$dsn] = new ExtendedPdo(
            $dsn,
            (string) getenv($this->username),
            (string) getenv($this->password),
            $this->options,
            $this->queries,
        );

        return self::$pdo[$dsn];
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
