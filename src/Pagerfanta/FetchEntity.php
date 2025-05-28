<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use PDO;

use function assert;
use function class_exists;

class FetchEntity implements FetcherInterface
{
    private readonly string $entity;

    /** @param class-string $entity */
    public function __construct(private readonly ExtendedPdoInterface $pdo, string $entity)
    {
        assert(class_exists($entity));
        $this->entity = $entity;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function __invoke(string $sql, array $params): array
    {
        $pdoStatement = $this->pdo->perform($sql, $params);

        return $pdoStatement->fetchAll(PDO::FETCH_FUNC, /** @param list<mixed> $args */fn (...$args) => /** @psalm-suppress MixedMethodCall */
            new $this->entity(...$args));
    }
}
