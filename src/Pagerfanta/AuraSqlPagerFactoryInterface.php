<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

interface AuraSqlPagerFactoryInterface
{
    /**
     * @param array<int|string|array<int|string>> $params
     */
    public function newInstance(ExtendedPdoInterface $pdo, string $sql, array $params, int $paging, string $uriTemplate, ?string $entity = null): AuraSqlPagerInterface;
}
