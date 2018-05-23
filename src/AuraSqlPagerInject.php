<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
    public function setAuraSqlPager(AuraSqlPagerFactoryInterface $pagerFactory)
    {
        $this->pagerFactory = $pagerFactory;
    }
}
