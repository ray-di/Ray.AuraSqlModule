<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Override;
use Pagerfanta\Adapter\AdapterInterface;
use PDO;
use PDOStatement;
use Ray\AuraSqlModule\Exception\ConnectionDetached;

use function assert;
use function call_user_func;

/**
 * @template T
 * @implements AdapterInterface<T>
 */
final class AuraSqlQueryAdapter implements AdapterInterface
{
    private readonly SelectInterface $select;

    /** Null once restored by unserialize() */
    private ExtendedPdoInterface|null $pdo;

    /** @var callable */
    private $countQueryBuilderModifier;

    /** @param callable $countQueryBuilderModifier a callable to modifier the query builder to count */
    public function __construct(ExtendedPdoInterface $pdo, SelectInterface $select, callable $countQueryBuilderModifier)
    {
        $this->pdo = $pdo;
        $this->select = clone $select;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier;
    }

    /**
     * Nothing is kept: a restored adapter cannot query, and Pagerfanta answers from the results it
     * already fetched.
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
        assert($data === []);
        $this->pdo = null;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getNbResults(): int
    {
        $pdo = $this->pdo();
        $select = $this->prepareCountQueryBuilder();
        $sql = $select->getStatement();
        $sth = $pdo->prepare($sql);
        assert($sth instanceof PDOStatement);
        $sth->execute($this->select->getBindValues());
        $result = $sth->fetchColumn();
        $nbResults = (int) $result;
        assert($nbResults >= 0);

        return $nbResults;
    }

    /**
     * {@inheritDoc}
     *
     * @return iterable<array-key, mixed>
     */
    #[Override]
    public function getSlice(int $offset, int $length): iterable
    {
        $pdo = $this->pdo();
        $select = clone $this->select;
        $sql = $select
            ->offset($offset)
            ->limit($length)
            ->getStatement();
        $sth = $pdo->prepare($sql);
        assert($sth instanceof PDOStatement);
        $sth->execute($this->select->getBindValues());

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    private function prepareCountQueryBuilder(): SelectInterface
    {
        $select = clone $this->select;
        call_user_func($this->countQueryBuilderModifier, $select);

        return $select;
    }

    private function pdo(): ExtendedPdoInterface
    {
        if ($this->pdo === null) {
            throw new ConnectionDetached();
        }

        return $this->pdo;
    }
}
