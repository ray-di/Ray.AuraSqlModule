<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use ArrayAccess;
use Aura\Sql\ExtendedPdoInterface;

/**
 * @extends ArrayAccess<int, Page>
 */
interface AuraSqlPagerInterface extends ArrayAccess
{
    /**
     * @param string       $sql
     * @param array<mixed> $params
     * @param int          $paging
     */
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator, ?string $entity = null): void;
}
