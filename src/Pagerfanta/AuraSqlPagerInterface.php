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
     * @param ExtendedPdoInterface    $pdo
     * @param string                  $sql
     * @param int                     $paging
     * @param RouteGeneratorInterface $routeGenerator
     */
    public function init(ExtendedPdoInterface $pdo, $sql, $paging, RouteGeneratorInterface $routeGenerator);

    /**
     * @param array $params
     * @param int   $page
     *
     * @return Pager
     */
    public function execute(array $params, $page);
}
