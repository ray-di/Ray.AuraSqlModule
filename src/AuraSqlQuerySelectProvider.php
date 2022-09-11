<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

/**
 * @implements ProviderInterface<SelectInterface>
 */
class AuraSqlQuerySelectProvider implements ProviderInterface
{
    private string $db;

    /**
     * @param string $db The database type
     *
     * @AuraSqlQueryConfig
     */
    #[AuraSqlQueryConfig()]
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): SelectInterface
    {
        return (new QueryFactory($this->db))->newSelect();
    }
}
