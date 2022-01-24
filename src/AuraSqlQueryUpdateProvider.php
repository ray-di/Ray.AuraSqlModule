<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;
use Aura\SqlQuery\QueryFactory;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

class AuraSqlQueryUpdateProvider implements ProviderInterface
{
    private string $db;

    /**
     * @param string $db The database type
     *
     * @AuraSqlQueryConfig
     */
    #[AuraSqlQueryConfig]
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     *
     * @return UpdateInterface
     */
    public function get()
    {
        return (new QueryFactory($this->db))->newUpdate();
    }
}
