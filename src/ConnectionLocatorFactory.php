<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocator;

use function explode;
use function preg_match;
use function sprintf;

final class ConnectionLocatorFactory
{
    private function __construct()
    {
    }

    public static function newInstance(string $dsn, string $user, string $password, string $slave): ConnectionLocator
    {
        $writes = ['master' => new Connection($dsn, $user, $password)];
        $i = 1;
        $slaves = explode(',', $slave);
        $reads = [];
        foreach ($slaves as $host) {
            $slaveDsn = self::changeHost($dsn, $host);
            $name = 'slave' . (string) $i++;
            $reads[$name] = new Connection($slaveDsn, $user, $password);
        }

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
