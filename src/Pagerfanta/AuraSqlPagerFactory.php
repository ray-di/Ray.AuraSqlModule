<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

class AuraSqlPagerFactory implements AuraSqlPagerFactoryInterface
{
    private \Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerInterface $auraSqlPager;

    public function __construct(AuraSqlPagerInterface $auraSqlPager)
    {
        $this->auraSqlPager = $auraSqlPager;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance(ExtendedPdoInterface $pdo, string $sql, array $params, int $paging, string $uriTemplate): AuraSqlPagerInterface
    {
        $this->auraSqlPager->init($pdo, $sql, $params, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlPager;
    }
}
