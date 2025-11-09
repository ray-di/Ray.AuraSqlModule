<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<SelectInterface> */
final readonly class AuraSqlQuerySelectProvider implements ProviderInterface
{
    /** @param string $db The database type */
    #[AuraSqlQueryConfig]
    public function __construct(private string $db)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): SelectInterface
    {
        return new QueryFactory($this->db)->newSelect();
    }
}
