<?php
/**
 * This file is part of the BEAR.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Doctrine\DBAL\Driver\Connection;

trait AuraSqlInject
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     *
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSql(ExtendedPdoInterface $db = null)
    {
        $this->db = $db;
    }
}
