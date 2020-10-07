<?php
namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactoryInterface;

trait AuraSqlPagerInject
{
    /**
     * @var AuraSqlPagerFactoryInterface
     */
    protected $pagerFactory;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlPager(AuraSqlPagerFactoryInterface $pagerFactory) : void
    {
        $this->pagerFactory = $pagerFactory;
    }
}
