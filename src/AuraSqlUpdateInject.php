<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;
use Ray\Di\Di\Inject;

trait AuraSqlUpdateInject
{
    /** @var UpdateInterface */
    protected $update;

    /**
     * @Inject
     */
    #[Inject]
    public function setAuraSqlUpdate(UpdateInterface $update): void
    {
        $this->update = $update;
    }
}
