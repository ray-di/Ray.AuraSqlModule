<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Ray\AuraSqlModule\Exception\AdapterDetached;
use Ray\Di\Injector;

use function assert;
use function iterator_to_array;
use function serialize;
use function unserialize;

class SerializableQueryPageTest extends AuraSqlQueryTestCase
{
    public function testARestoredPageRendersAndIteratesAsTheOriginal(): void
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlQueryPagerFactoryInterface::class);
        assert($factory instanceof AuraSqlQueryPagerFactoryInterface);
        $page = $factory->newInstance($this->pdo, $this->select, 5, '/?page={page}')[2];
        assert($page instanceof Page);

        $restored = unserialize(serialize($page));

        $this->assertInstanceOf(Page::class, $restored);
        $this->assertSame((string) $page, (string) $restored);
        $this->assertSame($page->data, $restored->data);
        $this->assertSame(iterator_to_array($page), iterator_to_array($restored));
    }

    public function testARestoredAdapterRefusesToQuery(): void
    {
        $adapter = unserialize(serialize(new AuraSqlQueryAdapter($this->pdo, $this->select, static fn ($select) => $select)));
        assert($adapter instanceof AuraSqlQueryAdapter);

        $this->expectException(AdapterDetached::class);
        $adapter->getSlice(0, 1);
    }

    public function testARestoredAdapterRefusesToCount(): void
    {
        $adapter = unserialize(serialize(new AuraSqlQueryAdapter($this->pdo, $this->select, static fn ($select) => $select)));
        assert($adapter instanceof AuraSqlQueryAdapter);

        $this->expectException(AdapterDetached::class);
        $adapter->getNbResults();
    }
}
