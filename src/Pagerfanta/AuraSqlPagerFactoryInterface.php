<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

interface AuraSqlPagerFactoryInterface
{
    /**
     * @param ExtendedPdoInterface $pdo
     * @param string               $sql
     * @param int                  $paging
     * @param string               $uriTemplate
     *
     * @return mixed
     */
    public function newInstance(ExtendedPdoInterface $pdo, $sql, $paging, $uriTemplate);
}
