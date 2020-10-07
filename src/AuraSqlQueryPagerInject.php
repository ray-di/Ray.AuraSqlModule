<?php
namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerFactoryInterface;

trait AuraSqlQueryPagerInject
{
    /**
     * @var AuraSqlQueryPagerFactoryInterface
     */
    protected $queryPagerFactory;

    /**
     * @\Ray\Di\Di\Inject
     */
    public function setAuraSqlQueryPager(AuraSqlQueryPagerFactoryInterface $queryPagerFactory) : void
    {
        $this->queryPagerFactory = $queryPagerFactory;
    }
}
