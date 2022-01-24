<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

interface AuraSqlQueryPagerInterface
{
    /**
     * @return AuraSqlQueryPagerInterface
     */
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, int $paging, RouteGeneratorInterface $routeGenerator);
}
