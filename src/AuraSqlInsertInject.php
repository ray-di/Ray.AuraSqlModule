<?php
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
    public function setAuraSqlInsert(InsertInterface $insert) : void
    {
        $this->insert = $insert;
    }
}
