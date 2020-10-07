<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\QueryFactory;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

class AuraSqlQueryInsertProvider implements ProviderInterface
{
    /** @var string */
    private $db;

    /**
     * @param string $db The database type
     *
     * @AuraSqlQueryConfig
     */
    public function __construct(string $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     *
     * @return InsertInterface
     */
    public function get()
    {
        return (new QueryFactory($this->db))->newInsert();
    }
}
