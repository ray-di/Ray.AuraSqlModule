<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\View\DefaultView;
use Ray\AuraSqlModule\FakeEntity;

class AuraSqlPagerFactoryTest extends AbstractPdoTestCase
{
    private AuraSqlPagerFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->factory = new AuraSqlPagerFactory(new AuraSqlPager(new DefaultView(), []));
    }

    public function testNewInstance(): void
    {
        $pager = $this->factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 1, '/{?page}');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);
        $page = $pager[1];
        $this->assertSame([
            [
                'id' => '1',
                'username' => 'BEAR',
                'post_content' => 'entry #1',
            ],
        ], $page->data);
        $this->assertInstanceOf(Page::class, $page);
    }

    public function testEntityPager(): void
    {
        $pager = $this->factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 1, '/{?page}', FakeEntity::class);
        $this->assertInstanceOf(AuraSqlPager::class, $pager);
        $page = $pager[1];
        $this->assertInstanceOf(Page::class, $page);
        $this->assertInstanceOf(FakeEntity::class, $page->data[0]);
    }
}
