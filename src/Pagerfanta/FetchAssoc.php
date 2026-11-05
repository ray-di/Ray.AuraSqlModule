<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use PDO;
use Ray\AuraSqlModule\Exception\ConnectionDetached;

final class FetchAssoc implements FetcherInterface
{
    /** Null once restored by unserialize() */
    private ExtendedPdoInterface|null $pdo;

    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-taint-sink sql $sql
     */
    #[Override]
    public function __invoke(string $sql, array $params): array
    {
        return $this->pdo()->perform($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * The connection is left out: a restored fetcher cannot query.
     *
     * @return array<never>
     */
    public function __serialize(): array
    {
        return [];
    }

    /** @param array<never> $data */
    public function __unserialize(array $data): void
    {
        $this->pdo = null;
    }

    private function pdo(): ExtendedPdoInterface
    {
        if ($this->pdo === null) {
            throw new ConnectionDetached();
        }

        return $this->pdo;
    }
}
