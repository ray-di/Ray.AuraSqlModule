<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

/**
 * @extends \ArrayAccess<int, Page>
 */
interface AuraSqlPagerInterface extends \ArrayAccess
{
    /**
     * @param ExtendedPdoInterface    $pdo
     * @param string                  $sql
     * @param array                   $params
     * @param int                     $paging
     * @param RouteGeneratorInterface $routeGenerator
     */
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator) : void;
}
