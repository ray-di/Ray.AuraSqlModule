<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;

trait AuraUpdateInject
{
    /**
     * @var UpdateInterface
     */
    protected $update;

    /**
     * @param UpdateInterface $update
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlQueryUpdate(UpdateInterface $update)
    {
        $this->update = $update;
    }
}
