<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Ray\AuraSqlModule\Exception\ConnectionDetached;
use Ray\AuraSqlModule\FakeEntity;
use Ray\AuraSqlModule\FakeRowsFetcher;
use Ray\Di\Injector;

use function assert;
use function iterator_to_array;
use function serialize;
use function unserialize;

/**
 * A fetched page can be stored (e.g. as part of a cached resource body) and rendered after it is
 * restored, without the connection it was fetched with.
 */
class SerializablePageTest extends AbstractPdoTestCase
{
    private function page(?string $entity = null): Page
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        assert($factory instanceof AuraSqlPagerFactoryInterface);
        $page = $factory->newInstance($this->pdo, 'SELECT * FROM posts', [], 5, '/?page={page}', $entity)[2];
        assert($page instanceof Page);

        return $page;
    }

    public function testARestoredPageRendersAndIteratesAsTheOriginal(): void
    {
        $page = $this->page();

        $restored = unserialize(serialize($page));

        $this->assertInstanceOf(Page::class, $restored);
        $this->assertSame((string) $page, (string) $restored);
        $this->assertSame($page->data, $restored->data);
        $this->assertSame(iterator_to_array($page), iterator_to_array($restored));
        $this->assertSame([50, 2, 10], [$restored->total, $restored->current, $restored->nbPages]);
    }

    public function testARestoredPageOfEntitiesRendersAsTheOriginal(): void
    {
        $page = $this->page(FakeEntity::class);

        $restored = unserialize(serialize($page));

        $this->assertSame((string) $page, (string) $restored);
        $this->assertEquals($page->data, $restored->data);
    }

    public function testARestoredAdapterRefusesToQuery(): void
    {
        $adapter = unserialize(serialize(new ExtendedPdoAdapter($this->pdo, 'SELECT * FROM posts', [])));
        assert($adapter instanceof ExtendedPdoAdapter);

        $this->expectException(ConnectionDetached::class);
        $adapter->getNbResults();
    }

    public function testARestoredAdapterRefusesToFetchASliceThroughACustomFetcher(): void
    {
        $adapter = unserialize(serialize(new ExtendedPdoAdapter($this->pdo, 'SELECT * FROM posts', [], new FakeRowsFetcher())));
        assert($adapter instanceof ExtendedPdoAdapter);

        $this->expectException(ConnectionDetached::class);
        $adapter->getSlice(0, 5);
    }

    public function testASerializedAdapterCarriesNoQuery(): void
    {
        $serialized = serialize(new ExtendedPdoAdapter($this->pdo, 'SELECT * FROM posts WHERE title = :title', ['title' => 'bound-value']));

        $this->assertStringNotContainsString('posts', $serialized);
        $this->assertStringNotContainsString('bound-value', $serialized);
    }

    public function testARestoredFetcherRefusesToQuery(): void
    {
        $fetcher = unserialize(serialize(new FetchAssoc($this->pdo)));
        assert($fetcher instanceof FetchAssoc);

        $this->expectException(ConnectionDetached::class);
        $fetcher('SELECT * FROM posts', []);
    }

    public function testARestoredEntityFetcherRefusesToQuery(): void
    {
        $fetcher = unserialize(serialize(new FetchEntity($this->pdo, FakeEntity::class)));
        assert($fetcher instanceof FetchEntity);

        $this->expectException(ConnectionDetached::class);
        $fetcher('SELECT * FROM posts', []);
    }
}
