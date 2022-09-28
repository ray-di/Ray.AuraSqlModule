<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Ray\Di\ProviderInterface;

/**
 * @implements ProviderInterface<ExtendedPdo>
 */

class ExtendedPdoProvider implements ProviderInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): ExtendedPdo
    {
        return ($this->connection)();
    }
}
