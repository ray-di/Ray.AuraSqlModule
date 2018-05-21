<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\FakePagerInject;
use Ray\Di\Injector;

class AuraSqlPagerModuleTest extends AbstractPdoTestCase
{
    public function testNewInstance()
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        /* @var  AuraSqlPagerFactoryInterface $factory */
        $this->assertInstanceOf(AuraSqlPagerFactory::class, $factory);
        $sql = 'SELECT * FROM posts';
        $pager = $factory->newInstance($this->pdo, $sql, [], 1, '/?page={page}&category=sports');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);

        return $pager;
    }

    public function testNewInstanceWithBinding()
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        /* @var AuraSqlPagerFactoryInterface $factory  */
        $this->assertInstanceOf(AuraSqlPagerFactory::class, $factory);
        $sql = 'SELECT * FROM posts where id = :id';
        $params = ['id' => 1];
        $pager = $factory->newInstance($this->pdo, $sql, $params, 1, '/?page={page}&category=sports');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);

        return $pager;
    }

    /**
     * @depends testNewInstance
     */
    public function testArrayAccess(AuraSqlPagerInterface $pager)
    {
        /** @var Page $page */
        $page = $pager[2];
        $this->assertTrue($page->hasNext);
        $this->assertTrue($page->hasPrevious);
        $expected = [
                [
                    'id' => '2',
                    'username' => 'BEAR',
                    'post_content' => 'entry #2',
                ],
        ];
        $this->assertSame($expected, $page->data);
        $expected = '<nav><a href="/?page=1&category=sports" rel="prev">Previous</a><a href="/?page=1&category=sports">1</a><span class="current">2</span><a href="/?page=3&category=sports">3</a><a href="/?page=4&category=sports">4</a><a href="/?page=5&category=sports">5</a><span class="dots">...</span><a href="/?page=50&category=sports">50</a><a href="/?page=3&category=sports" rel="next">Next</a></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(50, $page->total);
    }

    /**
     * @depends testNewInstance
     */
    public function testArrayAccessWithMaxPage(AuraSqlPagerInterface $pager)
    {
        /** @var Page $page */
        $page = $pager[50];
        $this->assertFalse($page->hasNext);
        $this->assertTrue($page->hasPrevious);
        $expected = [
                [
                    'id' => '50',
                    'username' => 'BEAR',
                    'post_content' => 'entry #50',
                ],
        ];
        $this->assertSame($expected, $page->data);
        $expected = '<nav><a href="/?page=49&category=sports" rel="prev">Previous</a><a href="/?page=1&category=sports">1</a><span class="dots">...</span><a href="/?page=46&category=sports">46</a><a href="/?page=47&category=sports">47</a><a href="/?page=48&category=sports">48</a><a href="/?page=49&category=sports">49</a><span class="current">50</span><span class="disabled">Next</span></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(50, $page->total);
    }

    /**
     * @depends testNewInstanceWithBinding
     */
    public function testArrayAccessWithBinding(AuraSqlPagerInterface $pager)
    {
        /* @var Page $page */
        $page = $pager[1];
        $this->assertFalse($page->hasNext);
        $this->assertFalse($page->hasPrevious);
        $expected = [
            [
                'id' => '1',
                'username' => 'BEAR',
                'post_content' => 'entry #1',
            ],
        ];
        $this->assertSame($expected, $page->data);
        $expected = '<nav><span class="disabled">Previous</span><span class="current">1</span><span class="disabled">Next</span></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(1, $page->total);
    }

    public function testInjectPager()
    {
        /* @var FakePagerInject $fakeInject */
        $fakeInject = (new Injector(new AuraSqlModule('')))->getInstance(FakePagerInject::class);
        list($pager, $queryPager) = $fakeInject->get();
        $this->assertInstanceOf(AuraSqlPagerFactoryInterface::class, $pager);
        $this->assertInstanceOf(AuraSqlQueryPagerFactoryInterface::class, $queryPager);
    }
}
