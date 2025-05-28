<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\QueryFactory;
use Override;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<DeleteInterface> */
final readonly class AuraSqlQueryDeleteProvider implements ProviderInterface
{
    /**
     * @param string $db The database type
     *
     * @AuraSqlQueryConfig
     */
    #[AuraSqlQueryConfig]
    public function __construct(private string $db)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function get(): DeleteInterface
    {
        return new QueryFactory($this->db)->newDelete();
    }
}
