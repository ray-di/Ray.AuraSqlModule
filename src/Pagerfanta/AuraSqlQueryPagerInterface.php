<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

interface AuraSqlQueryPagerInterface
{
    /**
     * @param ExtendedPdoInterface    $pdo
     * @param SelectInterface         $select
     * @param int                     $paging
     * @param RouteGeneratorInterface $routeGenerator
     *
     * @return AuraSqlQueryPagerInterface
     */
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, RouteGeneratorInterface $routeGenerator);
}
