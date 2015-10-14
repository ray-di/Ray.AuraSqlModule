<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;

trait AuraSqlInject
{
    /**
     * @var ExtendedPdoInterface
     */
    protected $pdo;

    /**
     * @param ExtendedPdoInterface $pdo
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSql(ExtendedPdoInterface $pdo = null)
    {
        $this->pdo = $pdo;
    }
}
