<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerFactoryInterface;
use Ray\Di\Di\Inject;

trait AuraSqlQueryPagerInject
{
    /** @var AuraSqlQueryPagerFactoryInterface */
    protected $queryPagerFactory;

    /**
     * @Inject
     */
    #[Inject]
    public function setAuraSqlQueryPager(AuraSqlQueryPagerFactoryInterface $queryPagerFactory): void
    {
        $this->queryPagerFactory = $queryPagerFactory;
    }
}
