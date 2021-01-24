<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Di\Inject;

/**
 * @codeCoverageIgnore
 */
trait AuraSqlInject
{
    /** @var ExtendedPdoInterface */
    protected $pdo;

    /**
     * @Inject()
     */
    #[Inject]
    public function setAuraSql(?ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }
}
