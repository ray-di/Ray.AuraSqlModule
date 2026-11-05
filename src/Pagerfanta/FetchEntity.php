<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use PDO;
use Ray\AuraSqlModule\Exception\AdapterDetached;

use function assert;
use function class_exists;

final class FetchEntity implements FetcherInterface
{
    private string $entity;

    /** Null once restored by unserialize() */
    private ExtendedPdoInterface|null $pdo;

    /** @param class-string $entity */
    public function __construct(ExtendedPdoInterface $pdo, string $entity)
    {
        assert(class_exists($entity));
        $this->pdo = $pdo;
        $this->entity = $entity;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-taint-sink sql $sql
     */
    #[Override]
    public function __invoke(string $sql, array $params): array
    {
        $pdoStatement = $this->pdo()->perform($sql, $params);

        return $pdoStatement->fetchAll(PDO::FETCH_FUNC, /** @param list<mixed> $args */fn (...$args) => /** @psalm-suppress MixedMethodCall */
            new $this->entity(...$args));
    }

    /**
     * The connection is left out: a restored fetcher cannot query.
     *
     * @return array{entity: string}
     */
    public function __serialize(): array
    {
        return ['entity' => $this->entity];
    }

    /** @param array{entity: string} $data */
    public function __unserialize(array $data): void
    {
        $this->pdo = null;
        $this->entity = $data['entity'];
    }

    private function pdo(): ExtendedPdoInterface
    {
        if ($this->pdo === null) {
            throw new AdapterDetached();
        }

        return $this->pdo;
    }
}
