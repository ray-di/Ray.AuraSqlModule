<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Ray\AuraSqlModule\AuraSqlModule;
use Ray\AuraSqlModule\FakePagerInject;
use Ray\Di\Injector;

use function assert;

class AuraSqlPagerModuleTest extends AbstractPdoTestCase
{
    public function testNewInstance(): AuraSqlPagerInterface
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        /** @var AuraSqlPagerFactoryInterface $factory */
        $this->assertInstanceOf(AuraSqlPagerFactory::class, $factory);
        $sql = 'SELECT * FROM posts';
        $pager = $factory->newInstance($this->pdo, $sql, [], 1, '/?page={page}&category=sports');
        $this->assertInstanceOf(AuraSqlPager::class, $pager);

        return $pager;
    }

    public function testNewInstanceWithBinding(): AuraSqlPagerInterface
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        /** @var AuraSqlPagerFactoryInterface $factory */
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
    public function testArrayAccess(AuraSqlPagerInterface $pager): void
    {
        $page = $pager[2];
        assert($page instanceof Page);
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
        $expected = '<nav class="pagination"><a class="pagination__item pagination__item--previous-page" href="/?page=1&category=sports" rel="prev">Previous</a><a class="pagination__item" href="/?page=1&category=sports">1</a><span class="pagination__item pagination__item--current-page">2</span><a class="pagination__item" href="/?page=3&category=sports">3</a><a class="pagination__item" href="/?page=4&category=sports">4</a><a class="pagination__item" href="/?page=5&category=sports">5</a><span class="pagination__item pagination__item--separator">&hellip;</span><a class="pagination__item" href="/?page=50&category=sports">50</a><a class="pagination__item pagination__item--next-page" href="/?page=3&category=sports" rel="next">Next</a></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(50, $page->total);
    }

    /**
     * @depends testNewInstance
     */
    public function testArrayAccessWithMaxPage(AuraSqlPagerInterface $pager)
    {
        $page = $pager[50];
        assert($page instanceof Page);
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
        $expected = '<nav class="pagination"><a class="pagination__item pagination__item--previous-page" href="/?page=49&category=sports" rel="prev">Previous</a><a class="pagination__item" href="/?page=1&category=sports">1</a><span class="pagination__item pagination__item--separator">&hellip;</span><a class="pagination__item" href="/?page=46&category=sports">46</a><a class="pagination__item" href="/?page=47&category=sports">47</a><a class="pagination__item" href="/?page=48&category=sports">48</a><a class="pagination__item" href="/?page=49&category=sports">49</a><span class="pagination__item pagination__item--current-page">50</span><span class="pagination__item pagination__item--next-page pagination__item--disabled">Next</span></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(50, $page->total);
    }

    /**
     * @depends testNewInstanceWithBinding
     */
    public function testArrayAccessWithBinding(AuraSqlPagerInterface $pager)
    {
        $page = $pager[1];
        assert($page instanceof Page);
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
        $expected = '<nav class="pagination"><span class="pagination__item pagination__item--previous-page pagination__item--disabled">Previous</span><span class="pagination__item pagination__item--current-page">1</span><span class="pagination__item pagination__item--next-page pagination__item--disabled">Next</span></nav>';
        $this->assertSame($expected, (string) $page);
        $this->assertSame(1, $page->total);
    }

    public function testInjectPager()
    {
        $fakeInject = (new Injector(new AuraSqlModule('')))->getInstance(FakePagerInject::class);
        assert($fakeInject instanceof FakePagerInject);
        [$pager, $queryPager] = $fakeInject->get();
        $this->assertInstanceOf(AuraSqlPagerFactoryInterface::class, $pager);
        $this->assertInstanceOf(AuraSqlQueryPagerFactoryInterface::class, $queryPager);
    }
}
