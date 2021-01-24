<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\SqlQuery\Common\SelectInterface;
use Ray\Di\Di\Inject;

trait AuraSqlSelectInject
{
    /** @var SelectInterface */
    protected $select;

    /**
     * @Inject
     */
    #[Inject]
    public function setAuraSqlSelect(SelectInterface $select): void
    {
        $this->select = $select;
    }
}
