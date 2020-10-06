<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Adapter\AdapterInterface;

class AuraSqlQueryAdapter implements AdapterInterface
{
    /**
     * @var ExtendedPdoInterface
     */
    private $pdo;

    /**
     * @var SelectInterface
     */
    private $select;

    /**
     * @var callable
     */
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
    public function getNbResults()
    {
        $select = $this->prepareCountQueryBuilder();
        $sql = $select->getStatement();
        $sth = $this->pdo->prepare($sql);
        $sth->execute($this->select->getBindValues());
        $result = $sth->fetchColumn();

        return (int) $result;
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-return array<int, array>
     */
    public function getSlice($offset, $length)
    {
        $select = clone $this->select;
        $sql = $select
            ->offset($offset)
            ->limit($length)
            ->getStatement();
        $sth = $this->pdo->prepare($sql);
        $sth->execute($this->select->getBindValues());
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        \assert(\is_array($result));

        return $result;
    }

    private function prepareCountQueryBuilder() : SelectInterface
    {
        $select = clone $this->select;
        \call_user_func($this->countQueryBuilderModifier, $select);

        return $select;
    }
}
