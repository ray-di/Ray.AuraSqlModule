<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;

/**
 * @codeCoverageIgnore
 */
trait AuraSqlInject
{
    /** @var ExtendedPdoInterface */
    protected $pdo;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSql(?ExtendedPdoInterface $pdo = null)
    {
        $this->pdo = $pdo;
    }
}
