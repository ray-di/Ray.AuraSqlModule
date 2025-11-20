<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Override;

final class AuraSqlQueryPagerFactory implements AuraSqlQueryPagerFactoryInterface
{
    public function __construct(private AuraSqlQueryPagerInterface $auraSqlQueryPager)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function newInstance(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, $uriTemplate)
    {
        $this->auraSqlQueryPager->init($pdo, $select, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlQueryPager;
    }
}
