<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
    public function setAuraSqlSelect(SelectInterface $select)
    {
        $this->select = $select;
    }
}
