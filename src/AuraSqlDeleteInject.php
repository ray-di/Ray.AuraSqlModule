<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\DeleteInterface;

trait AuraSqlDeleteInject
{
    /**
     * @var DeleteInterface
     */
    protected $delete;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlDelete(DeleteInterface $delete)
    {
        $this->delete = $delete;
    }
}
