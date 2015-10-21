<?php
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\View\DefaultView;

class AuraSqlPagerFactoryTest extends AbstractPdoTestCase
{
    /**
     * @var AuraSqlPagerFactory
     */
    private $factory;

    public function setUp()
    {
        parent::setUp();
        $this->factory = new AuraSqlPagerFactory(new AuraSqlPager(new DefaultView, []));
    }

    public function testNewInstance()
    {
        $pager = $this->factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 1, new DefaultRouteGenerator('/{?page}'));
        $this->assertInstanceOf(AuraSqlPager::class, $pager);
        $page = $pager[1];
        $this->assertInstanceOf(Page::class, $page);
    }
}
