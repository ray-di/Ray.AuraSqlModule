<?php
/**
 * This file is part of the *** package
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
     * @return mixed
     */
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, RouteGeneratorInterface $routeGenerator);

    /**
     * @param array $params
     * @param int   $page
     *
     * @return Pager
     */
    public function execute(array $params, $page);
}
