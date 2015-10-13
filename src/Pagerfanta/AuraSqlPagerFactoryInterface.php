<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

interface AuraSqlPagerFactoryInterface
{
    /**
     * @param ExtendedPdoInterface $pdo
     * @param string               $sql
     * @param string               $page
     * @param int                  $paging
     *
     * @return AuraSqlPager
     */
    public function newInstance(ExtendedPdoInterface $pdo, $sql, $page, $paging);
}
