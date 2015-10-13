<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\PagerFanta;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Adapter\AdapterInterface;

class ExtendedPdoAdapter implements AdapterInterface
{
    /**
     * @var ExtendedPdo
     */
    private $pdo;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var
     */
    private $params;

    public function __construct(ExtendedPdoInterface $pdo, $sql, array $params)
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        // be smart and try to guess the total number of records
        $countQuery = $this->rewriteCountQuery($this->sql);
        if (! $countQuery) {
            // GROUP BY => fetch the whole result set and count the rows returned
            $result = $this->pdo->query($this->sql)->fetchAll();
            $count = count($result);

            return (integer) $count;
        }
        if ($this->params) {
            $sth = $this->pdo->prepareWithValues($this->sql, $this->params);
            $sth->execute();
            $count = $sth->fetchAll();

            return (integer) $count;
        }
        $count = $this->pdo->query($countQuery)->fetchColumn();

        return (integer) $count;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $sql = $this->sql . $this->getLimitClause($offset, $length);
        $result = $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimitClause($offset, $length)
    {
        $has_limit = $offset || $length;
        if ($offset && $length) {
            $clause = PHP_EOL . "LIMIT {$length}";
            if ($offset) {
                $clause .= " OFFSET {$offset}";
            }
            return $clause;
        } elseif ($has_limit && $length) {
            return PHP_EOL . "LIMIT {$length}";
        }

        return '';
    }


    /**
     * Return count query
     *
     * @param string $query
     *
     * @return string
     *
     * Taken from pear/pager and modified.
     * @see https://github.com/pear/Pager/blob/master/examples/Pager_Wrapper.php
     *
     */
    public function rewriteCountQuery($query)
    {
        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $query) || preg_match('/\s+GROUP\s+BY\s+/is', $query)) {
            return false;
        }
        $openParenthesis = '(?:\()';
        $closeParenthesis = '(?:\))';
        $subQueryInSelect = $openParenthesis . '.*\bFROM\b.*' . $closeParenthesis;
        $pattern = '/(?:.*' . $subQueryInSelect . '.*)\bFROM\b\s+/Uims';
        if (preg_match($pattern, $query)) {
            return false;
        }
        $subQueryWithLimitOrder = $openParenthesis . '.*\b(LIMIT|ORDER)\b.*' . $closeParenthesis;
        $pattern = '/.*\bFROM\b.*(?:.*' . $subQueryWithLimitOrder . '.*).*/Uims';
        if (preg_match($pattern, $query)) {
            return false;
        }
        $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $query, 1);
        list($queryCount,) = preg_split('/\s+ORDER\s+BY\s+/is', $queryCount);
        list($queryCount,) = preg_split('/\bLIMIT\b/is', $queryCount);

        return trim($queryCount);
    }
}
