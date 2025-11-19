<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;
use Aura\SqlQuery\QueryFactory;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<UpdateInterface> */
final readonly class AuraSqlQueryUpdateProvider implements ProviderInterface
{
    /** @param string $db The database type */
    public function __construct(
        #[AuraSqlQueryConfig]
        private string $db
    ) {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): UpdateInterface
    {
        return new QueryFactory($this->db)->newUpdate();
    }
}
