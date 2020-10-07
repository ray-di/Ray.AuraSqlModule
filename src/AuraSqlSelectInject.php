<?php
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\SelectInterface;

trait AuraSqlSelectInject
{
    /**
     * @var SelectInterface
     */
    protected $select;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlSelect(SelectInterface $select) : void
    {
        $this->select = $select;
    }
}
