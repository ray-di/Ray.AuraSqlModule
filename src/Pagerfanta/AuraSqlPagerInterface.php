<?php
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
     * @param array<mixed>            $params
     * @param int                     $paging
     * @param RouteGeneratorInterface $routeGenerator
     */
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator) : void;
}
