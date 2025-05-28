<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\QueryFactory;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<InsertInterface> */
class AuraSqlQueryInsertProvider implements ProviderInterface
{
    /**
     * @param string $db The database type
     *
     * @AuraSqlQueryConfig
     */
    #[AuraSqlQueryConfig]
    public function __construct(private readonly string $db)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): InsertInterface
    {
        return (new QueryFactory($this->db))->newInsert();
    }
}
