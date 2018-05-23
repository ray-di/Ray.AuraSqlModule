<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\QueryFactory;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\Di\ProviderInterface;

class AuraSqlQueryInsertProvider implements ProviderInterface
{
    /**
     * @var string
     */
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
     */
    public function get()
    {
        return (new QueryFactory($this->db))->newInsert();
    }
}
