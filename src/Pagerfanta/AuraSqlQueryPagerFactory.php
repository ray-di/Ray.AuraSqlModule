<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

class AuraSqlQueryPagerFactory implements AuraSqlQueryPagerFactoryInterface
{
    private \Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerInterface $auraSqlQueryPager;

    public function __construct(AuraSqlQueryPagerInterface $auraSqlQueryPager)
    {
        $this->auraSqlQueryPager = $auraSqlQueryPager;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, $uriTemplate)
    {
        $this->auraSqlQueryPager->init($pdo, $select, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlQueryPager;
    }
}
