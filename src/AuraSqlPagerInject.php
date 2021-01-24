<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactoryInterface;
use Ray\Di\Di\Inject;

trait AuraSqlPagerInject
{
    /** @var AuraSqlPagerFactoryInterface */
    protected $pagerFactory;

    /**
     * @Inject
     */
    #[Inject]
    public function setAuraSqlPager(AuraSqlPagerFactoryInterface $pagerFactory): void
    {
        $this->pagerFactory = $pagerFactory;
    }
}
