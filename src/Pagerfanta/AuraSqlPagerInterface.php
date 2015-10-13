<?php
/**
 * This file is part of the *** package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

interface AuraSqlPagerInterface
{
    /**
     * @param ExtendedPdoInterface $pdo
     * @param string               $sql
     * @param int                  $page
     * @param int                  $paging
     *
     * @return mixed
     */
    public function init(ExtendedPdoInterface $pdo, $sql, $page, $paging);
}
