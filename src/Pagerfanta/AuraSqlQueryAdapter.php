<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Adapter\AdapterInterface;

class AuraSqlQueryAdapter implements AdapterInterface
{
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
     * Constructor.
     *
     * @param Select   $select
     * @param callable $countQueryBuilderModifier a callable to modifier the query builder to count
     */

    /**
     * @param ExtendedPdo     $pdo
     * @param SelectInterface $select
     * @param callable        $countQueryBuilderModifier
     */
    public function __construct(ExtendedPdo $pdo, SelectInterface $select, callable $countQueryBuilderModifier)
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

        return $result;
    }

    private function prepareCountQueryBuilder()
    {
        $select = clone $this->select;
        \call_user_func($this->countQueryBuilderModifier, $select);

        return $select;
    }
}
