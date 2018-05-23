<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\InsertInterface;

trait AuraSqlInsertInject
{
    /**
     * @var InsertInterface
     */
    protected $insert;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlInsert(InsertInterface $insert)
    {
        $this->insert = $insert;
    }
}
