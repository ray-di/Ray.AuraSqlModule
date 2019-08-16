<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\Exception\LogicException;
use Pagerfanta\View\DefaultView;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlQueryPagerTest extends AuraSqlQueryTestCase
{
    /**
     * @var AuraSqlQueryPager
     */
    private $pager;

    public function setUp() : void
    {
        parent::setUp();
        $this->pager = new AuraSqlQueryPager(new DefaultView, []);
    }

    public function testExecute()
    {
        $this->expectException(NotInitialized::class);
        $pager = $this->pager;
        $pager[1];
    }

    public function testOffsetExists()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        isset($pager[1]);
    }

    public function testOffsetSet()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        $pager[1] = 1;
    }

    public function testOffsetUnset()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        unset($pager[1]);
    }

    public function testOffsetGet()
    {
        $this->select = $this->qf->newSelect();
        $this->select->cols(['p.username'])->from('posts as p')->orderBy(['p.username']);
        $pager = $this->pager;
        $pager->init($this->pdo, $this->select, 1, new DefaultRouteGenerator('/?page=1'));
        $post = $pager[2];
        $this->assertTrue($post->hasNext);
        $this->assertTrue($post->hasPrevious);
        $this->assertSame(1, $post->maxPerPage);
        $this->assertSame(2, $post->current);
        $this->assertSame(50, $post->total);
        $expected = [['username' => 'Jon Doe']];
        $this->assertSame($expected, $post->data);
    }

    public function estOffsetGetWithoutInit()
    {
        $this->select = $this->qf->newSelect();
        $this->select->cols(['p.username'])->from('posts as p');
        $pager = new AuraSqlQueryPager(new DefaultView, []);
        $post = $pager[2];
    }
}
