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
     * @param int                  $page
     * @param string               $uriTemplate
     *
     * @return AuraSqlPagerInterface
     */
    public function newInstance(ExtendedPdoInterface $pdo, $sql, $paging, $uriTemplate);
}
