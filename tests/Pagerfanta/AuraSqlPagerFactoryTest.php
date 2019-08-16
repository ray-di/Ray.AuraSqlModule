<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\View\DefaultView;

class AuraSqlPagerFactoryTest extends AbstractPdoTestCase
{
    /**
     * @var AuraSqlPagerFactory
     */
    private $factory;

    public function setUp() : void
    {
        parent::setUp();
        $this->factory = new AuraSqlPagerFactory(new AuraSqlPager(new DefaultView, []));
    }

    public function testNewInstance()
    {
        $pager = $this->factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 1, '/{?page}');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);
        $page = $pager[1];
        $this->assertInstanceOf(Page::class, $page);
    }
}
