<?php
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
    public function setAuraSqlDelete(DeleteInterface $delete) : void
    {
        $this->delete = $delete;
    }
}
