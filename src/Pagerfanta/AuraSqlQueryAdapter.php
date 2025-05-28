<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Override;
use Pagerfanta\Adapter\AdapterInterface;
use PDO;
use PDOStatement;

use function assert;
use function call_user_func;

/**
 * @template T
 * @implements AdapterInterface<T>
 */
class AuraSqlQueryAdapter implements AdapterInterface
{
    private readonly SelectInterface $select;

    /** @var callable */
    private $countQueryBuilderModifier;

    /** @param callable $countQueryBuilderModifier a callable to modifier the query builder to count */
    public function __construct(private readonly ExtendedPdoInterface $pdo, SelectInterface $select, callable $countQueryBuilderModifier)
    {
        $this->select = clone $select;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier;
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function getNbResults(): int
    {
        $select = $this->prepareCountQueryBuilder();
        $sql = $select->getStatement();
        $sth = $this->pdo->prepare($sql);
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
        $select = clone $this->select;
        $sql = $select
            ->offset($offset)
            ->limit($length)
            ->getStatement();
        $sth = $this->pdo->prepare($sql);
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
}
