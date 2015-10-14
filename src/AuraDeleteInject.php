<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\DeleteInterface;

trait AuraDeleteInject
{
    /**
     * @var DeleteInterface
     */
    protected $delete;

    /**
     * @param DeleteInterface $delete
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlQueryUpdate(DeleteInterface $delete)
    {
        $this->delete = $delete;
    }
}
