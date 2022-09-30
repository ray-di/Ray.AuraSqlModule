<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;

use function explode;
use function preg_match;
use function sprintf;

final class ConnectionLocatorFactory
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @param array<string> $options
     * @param array<string> $queries
     */
    public static function fromInstance(
        string $dsn,
        string $user,
        string $password,
        string $slave,
        array $options,
        array $queries
    ): ConnectionLocator {
        $writes = ['master' => new Connection($dsn, $user, $password, $options, $queries)];
        $i = 1;
        $slaves = explode(',', $slave);
        $reads = [];
        foreach ($slaves as $host) {
            $slaveDsn = self::changeHost($dsn, $host);
            $name = 'slave' . (string) $i++;
            $reads[$name] = new Connection($slaveDsn, $user, $password, $options, $queries);
        }

        return new ConnectionLocator(null, $reads, $writes);
    }

    /**
     * @param array<string> $options
     * @param array<string> $queries
     */
    public static function fromEnv(
        string $dsn,
        string $username,
        string $password,
        string $slave,
        array $options,
        array $queries
    ): ConnectionLocator {
        $writes = ['master' => new EnvConnection($dsn, null, $username, $password, $options, $queries)];
        $reads = ['slave' => new EnvConnection($dsn, $slave, $username, $password, $options, $queries)];

        return new ConnectionLocator(null, $reads, $writes);
    }

    private static function changeHost(string $dsn, string $host): string
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
