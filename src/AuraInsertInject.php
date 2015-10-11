<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\InsertInterface;

trait AuraInsertInject
{
    /**
     * @var InsertInterface
     */
    protected $insert;

    /**
     * @param InsertInterface $insert
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlQueryInsert(InsertInterface $insert)
    {
        $this->insert = $insert;
    }
}
