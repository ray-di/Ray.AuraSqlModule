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
     * @param array                   $params
     * @param int                     $paging
     * @param RouteGeneratorInterface $routeGenerator
     */
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator);

    /**
     * @param int   $page
     *
     * @return Page
     */
    public function execute($page);
}
