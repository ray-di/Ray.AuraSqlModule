<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Pagerfanta\FetcherInterface;

/** A custom fetcher that answers without the adapter's connection, as one opening its own would. */
final class FakeRowsFetcher implements FetcherInterface
{
    /** @inheritDoc */
    public function __invoke(string $sql, array $params): array
    {
        return [['id' => 1]];
    }
}
