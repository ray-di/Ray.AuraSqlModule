<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Exception\InvalidArgumentException;

class AuraSqlQueryAdapter implements AdapterInterface
{
    private $pdo;

    /**
     * @var Select
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
     * @param callable $countQueryBuilderModifier A callable to modifier the query builder to count.
     */

    /**
     * @param ExtendedPdo     $pdo
     * @param SelectInterface $select
     * @param callable        $countQueryBuilderModifier
     */
    public function __construct(ExtendedPdo $pdo, SelectInterface $select, callable $countQueryBuilderModifier)
    {
        $this->pdo = $pdo;
        if (!is_callable($countQueryBuilderModifier)) {
            throw new InvalidArgumentException('The count query builder modifier must be a callable.');
        }

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
        $sth->execute();
        $result = $sth->fetchColumn();

        return (int) $result;
    }

    private function prepareCountQueryBuilder()
    {
        $select = clone $this->select;
        call_user_func($this->countQueryBuilderModifier, $select);

        return $select;
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
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}
