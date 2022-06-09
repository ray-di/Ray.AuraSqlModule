<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

interface FetcherInterface
{
    /**
     * @param array<mixed> $params
     *
     * @retrun array<mixed>
     */
    public function __invoke(string $sql, array $params): array;
}
