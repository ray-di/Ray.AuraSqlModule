<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Adapter\AdapterInterface;
use PDO;
use PDOStatement;

use function assert;
use function call_user_func;
use function is_array;
use function is_int;

class AuraSqlQueryAdapter implements AdapterInterface
{
    private ExtendedPdoInterface $pdo;
    private SelectInterface $select;

    /** @var callable */
    private $countQueryBuilderModifier;

    /**
     * @param callable $countQueryBuilderModifier a callable to modifier the query builder to count
     */
    public function __construct(ExtendedPdoInterface $pdo, SelectInterface $select, callable $countQueryBuilderModifier)
    {
        $this->pdo = $pdo;
        $this->select = clone $select;
        $this->countQueryBuilderModifier = $countQueryBuilderModifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults(): int
    {
        $select = $this->prepareCountQueryBuilder();
        $sql = $select->getStatement();
        $sth = $this->pdo->prepare($sql);
        assert($sth instanceof PDOStatement);
        $sth->execute($this->select->getBindValues());
        $result = $sth->fetchColumn();
        $nbResults = (int) $result;
        assert($nbResults > 0);
        assert(is_int($nbResults));

        return $nbResults;
    }

    /**
     * {@inheritdoc}
     *
     * @return iterable<array-key, mixed>
     */
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
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        assert(is_array($result));

        return $result;
    }

    private function prepareCountQueryBuilder(): SelectInterface
    {
        $select = clone $this->select;
        call_user_func($this->countQueryBuilderModifier, $select);

        return $select;
    }
}
