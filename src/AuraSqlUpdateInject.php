<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\UpdateInterface;

trait AuraSqlUpdateInject
{
    /** @var UpdateInterface */
    protected $update;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlUpdate(UpdateInterface $update): void
    {
        $this->update = $update;
    }
}
