<?php
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;

trait AuraSqlInject
{
    /**
     * @var ExtendedPdoInterface
     */
    protected $pdo;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSql(ExtendedPdoInterface $pdo = null)
    {
        $this->pdo = $pdo;
    }
}
