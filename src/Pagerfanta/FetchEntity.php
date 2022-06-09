<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use PDO;
use function assert;
use function class_exists;

class FetchEntity implements FetcherInterface
{
    private ExtendedPdoInterface $pdo;
    private string $entity;

    /**
     * @param class-string $entity
     */
    public function __construct(ExtendedPdoInterface $pdo, string $entity)
    {
        assert(class_exists($entity));
        $this->pdo = $pdo;
        $this->entity = $entity;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(string $sql, array $params): array
    {
        $pdoStatement = $this->pdo->perform($sql, $params);

        return $pdoStatement->fetchAll(PDO::FETCH_FUNC, /** @param list<mixed> $args */function (...$args) {
            /** @psalm-suppress MixedMethodCall */
            return new $this->entity(...$args);
        });
    }
}
