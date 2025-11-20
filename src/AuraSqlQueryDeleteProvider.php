<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\QueryFactory;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<DeleteInterface> */
final class AuraSqlQueryDeleteProvider implements ProviderInterface
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
    public function get(): DeleteInterface
    {
        return (new QueryFactory($this->db))->newDelete();
    }
}
