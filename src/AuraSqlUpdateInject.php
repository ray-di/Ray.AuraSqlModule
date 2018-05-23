<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;

trait AuraSqlUpdateInject
{
    /**
     * @var UpdateInterface
     */
    protected $update;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlUpdate(UpdateInterface $update)
    {
        $this->update = $update;
    }
}
