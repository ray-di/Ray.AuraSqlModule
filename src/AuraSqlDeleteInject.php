<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\DeleteInterface;
use Ray\Di\Di\Inject;

trait AuraSqlDeleteInject
{
    /** @var DeleteInterface */
    protected $delete;

    /**
     * @Inject
     */
    #[Inject]
    public function setAuraSqlDelete(DeleteInterface $delete): void
    {
        $this->delete = $delete;
    }
}
