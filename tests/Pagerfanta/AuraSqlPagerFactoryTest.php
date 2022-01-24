<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\View\DefaultView;

class AuraSqlPagerFactoryTest extends AbstractPdoTestCase
{
    private \Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new AuraSqlPagerFactory(new AuraSqlPager(new DefaultView(), []));
    }

    public function testNewInstance()
    {
        $pager = $this->factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 1, '/{?page}');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);
        $page = $pager[1];
        $this->assertInstanceOf(Page::class, $page);
    }
}
