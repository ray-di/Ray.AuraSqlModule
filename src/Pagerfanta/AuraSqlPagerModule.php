<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\View\DefaultView;
use Pagerfanta\View\Template\DefaultTemplate;
use Pagerfanta\View\Template\TemplateInterface;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\Di\AbstractModule;

class AuraSqlPagerModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ViewInterface::class)->to(DefaultView::class);
        $this->bind(TemplateInterface::class)->to(DefaultTemplate::class);
        $this->bind(AuraSqlPagerInterface::class)->to(AuraSqlPager::class);
        $this->bind(AuraSqlPagerFactoryInterface::class)->to(AuraSqlPagerFactory::class);
        $this->bind(AuraSqlQueryPagerFactoryInterface::class)->to(AuraSqlQueryPagerFactory::class);
        $this->bind(AuraSqlQueryPagerInterface::class)->to(AuraSqlQueryPager::class);
        $this->bind('')->annotatedWith(PagerViewOption::class)->toInstance([]);
    }
}
