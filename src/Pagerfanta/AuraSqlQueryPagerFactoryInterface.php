<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

interface AuraSqlQueryPagerFactoryInterface
{
    /**
     * @param ExtendedPdoInterface $pdo
     * @param SelectInterface      $select
     * @param int                  $paging
     * @param string               $uriTemplate
     *
     * @return AuraSqlQueryPager
     */
    public function newInstance(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, $uriTemplate);
}
