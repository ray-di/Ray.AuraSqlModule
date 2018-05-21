<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

interface AuraSqlPagerFactoryInterface
{
    public function newInstance(ExtendedPdoInterface $pdo, string $sql, array $params, int $paging, string $uriTemplate) : AuraSqlPagerInterface;
}
